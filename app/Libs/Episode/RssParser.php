<?php

namespace App\Libs\Episode;

use Exception;
use Carbon\Carbon;
use App\Models\Episode;

class RssParser extends AbstractParser
{
    /**
     * Parse new episodes.
     *
     * @param  mixed $url
     * @return array
     */
    public function parse($url)
    {
        $scheme = parse_url($url, PHP_URL_SCHEME);
        $host = parse_url($url, PHP_URL_HOST);
        $path = parse_url($url, PHP_URL_PATH);

        $body = null;

        $response = $this->client->get($url);
        $body = $response->getBody();

        $xml = simplexml_load_string($body, 'SimpleXMLElement', LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json, true);

        $episodes = [];

        if (empty($array['channel']['item'])) {
            return $episodes;
        }

        foreach ($array['channel']['item'] as $item) {
            $episode = new Episode;

            // url
            $episode->url = $item['link'];

            // series id
            preg_match('#/series/([^/]+)/#', $item['link'], $m);
            if (!empty($m[1])) {
                $episode->series_id = trim($m[1]);
            }

            // Обратная сторона (Counterpart). Что-то позаимствованное. (S02E03)
            preg_match('/(.+?) \((.+?)\)\.(.+?)\. \(S(\d+)E(\d+)\)/', $item['title'], $m);
            if (count($m) < 6) continue;
            list($_, $name_ru, $name_en, $name, $s, $ep) = $m;

            // episode name
            $episode->name_ru = trim($name);

            // season/episode number
            $episode->season = (int) $s;
            $episode->episode = (int) $ep;

            // release date
            $episode->released_at = Carbon::parse($item['pubDate']);

            // meta
            $meta = [];
            // store series data for denormalization and on-demand creation
            $meta['series']['id'] = $episode->series_id;
            $meta['series']['name_ru'] = trim($name_ru);
            $meta['series']['name_en'] = trim($name_en);

            $episode->meta = $meta;

            // keywords: en version, series name, etc... will be added with Episode model mutator.
            $episode->keywords = $episode->name;

            $episodes[] = $episode;
        }

        return $episodes;
    }
}

<?php

namespace App\Libs\Episode;

use Exception;
use Carbon\Carbon;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Episode;

class SiteParser extends AbstractParser
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

        $crawler = new Crawler;
        $crawler->addContent($body);

        $episodes = $crawler->filter('div.body > div.row')->each(function ($node, $i) use ($scheme, $host) {
            $episode = new Episode;

            // url
            $href = trim($node->filter('a')->attr('href'));
            $episode->url = sprintf('%s://%s%s', $scheme, $host, $href);

            // series id
            preg_match('#/series/([^/]+)/#', $href, $m);
            if (!empty($m[1])) {
                $episode->series_id = trim($m[1]);
            }

            // episode name
            $episode->name_ru = trim($node->filter('div.details-pane > div.alpha')->first()->text());
            $episode->name_en = trim($node->filter('div.details-pane > div.beta')->first()->text());

            // season/episode number
            $number = trim($node->filter('div.left-part')->text());
            // "2 сезон 12 серия" - do no use exact "сезон/серия" words in sscanf for possible plural forms
            list($s, $_, $ep, $_) = sscanf($number, '%d %s %d %s');
            $episode->season = $s;
            $episode->episode = $ep;

            // release date
            $date = $node->filter('div.right-part')->text();
            $episode->released_at = Carbon::parse($date);

            // meta
            $meta = [];
            // store series data for denormalization and on-demand creation
            $meta['series']['id'] = $episode->series_id;
            $meta['series']['name_ru'] = trim($node->filter('div.name-ru')->text());
            $meta['series']['name_en'] = trim($node->filter('div.name-en')->text());

            $episode->meta = $meta;

            // keywords: en version, series name, etc... will be added with Episode model mutator.
            $episode->keywords = $episode->name_ru;

            return $episode;
        });

        return $episodes;
    }
}

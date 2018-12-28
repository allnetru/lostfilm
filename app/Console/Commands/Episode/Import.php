<?php

namespace App\Console\Commands\Episode;

use Exception;
use App\Models\Series;
use App\Models\Episode;
use Illuminate\Console\Command;

class Import extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'episode:import {--url=} {--parser=site}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'LostFilm new episodes importer';

    /**
     * Import URL.
     *
     * @var string
     */
    protected $url;

    /**
     * Parser.
     * Example: site, rss.
     *
     * @var string
     */
    protected $parser;

    /**
     * Parser classes.
     *
     * @var array
     */
    protected $parsers = [
        'site' => \App\Libs\Episode\SiteParser::class,
        'rss' => \App\Libs\Episode\RssParser::class,
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->url = $this->option('url') ?? config('services.lostfilm.episodes.url');
        $this->parser = $this->option('parser');

        $last = Episode::latest()->first();
        $this->parse($last);

        $this->info('LostFilm Parser Done!');
    }

    /**
     * Parse.
     *
     * @param  App\Models\Episode $last Last parsed episode.
     * @return void
     */
    private function parse($last)
    {
        if (!isset($this->parsers[$this->parser])) {
            $this->error('Parser not found: ' . $this->parser);
            exit(1);
        }

        $parser = new $this->parsers[$this->parser];

        $episodes = $parser->parse($this->url);

        foreach ($episodes as $episode) {
            // stop on first existed episode
            if ($last && $last->url == $episode->url) {
                $this->info('Data is up to date');
                break;
            }

            $this->line('Find new episode: ' . $episode->series_id . ' ' . $episode->number);
            if (($series = Series::firstOrCreate($episode->meta['series']))) {
                $episode->save();
            }
        }
    }
}

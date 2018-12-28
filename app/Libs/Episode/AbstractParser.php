<?php

namespace App\Libs\Episode;

use GuzzleHttp\Client;

abstract class AbstractParser
{

    /**
     * Guzzle client object.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Client headers.
     *
     * @var array
     */
    protected $headers = [
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        'User-Agent' => 'Mozilla/5.0 (compatible; LostFilmBot/1.0; +https://shopnetic.com/)',
    ];

    /**
     * Timeout.
     *
     * @var int
     */
    protected $timeout = 15;

    /**
     * Create a new parser instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new Client(['connect_timeout' => 5, 'timeout' => $this->timeout, 'headers' => $this->headers]);
    }

    /**
     * Parse new episodes.
     *
     * @param  mixed $url
     * @return array
     */
    abstract public function parse($url);
}

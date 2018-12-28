<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EpisodeController extends Controller
{
    /**
     * Show the list of new episodes.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orm = Episode::orderBy('released_at', 'desc');

        $query = $request->get('query');
        if ($query && mb_strlen($query) >= 3) {
            $orm->where('keywords', 'like', '%' . mb_strtolower($query) . '%');
        }
        
        $episodes = $orm->paginate(10);

        // series eager loading isn't necessary, cause of flat denormalization in meta field
        // $episodes->load('series');
        // instead of $episode->series->name_ru use $episode->meta['series']['name_ru']

        return view('episode.list', ['episodes' => $episodes, 'query' => $query]);
    }
}

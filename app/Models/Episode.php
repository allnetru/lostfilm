<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'number',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'meta' => 'array',
        'released_at' => 'date',
    ];

    /**
     * Get the series record associated with the episode.
     */
    public function series()
    {
        return $this->hasOne('App\Models\Series', 'id', 'series_id');
    }

    /**
     * Set the status.
     *
     * @param  string $value
     * @return void
     */
    public function setKeywordsAttribute($value)
    {
        $keywords = [
            $this->name_ru, $this->name_en,
            $this->meta['series']['name_ru'], $this->meta['series']['name_en'],
        ];
        $this->attributes['keywords'] = trim(mb_strtolower(implode(' ', $keywords)));
    }

    /**
     * Get the season and episode number.
     * S01EP12.
     *
     * @param string $value
     *
     * @return string
     */
    public function getNumberAttribute($value)
    {
        return sprintf('S%02dEP%02d', $this->season, $this->episode);
    }
}

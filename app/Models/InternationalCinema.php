<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternationalCinema extends Model
{
    protected $table = 'international_cinema';
    protected $guarded = [];

    public function curatedSection()
    {
        return $this->belongsTo(CuratedSection::class, 'curated_section_id');
    }
}

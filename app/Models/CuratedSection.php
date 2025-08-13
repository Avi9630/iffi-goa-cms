<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuratedSection extends Model
{
    protected $table = 'curated_sections';
    protected $guarded = [];

    public function internationalCinema()
    {
        return $this->hasMany(InternationalCinema::class, 'curated_section_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndianPanoramaOfficialSelection extends Model
{
    protected $table = 'indian_panorama_official_selections';
    protected $guarded = [];

    public function indianPanorama()
    {
        return $this->hasMany(IndianPanorama::class, 'official_selection_id');
    }
}

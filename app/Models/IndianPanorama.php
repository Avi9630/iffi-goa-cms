<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndianPanorama extends Model
{
    protected $table = 'indian_panorama_cinema';
    protected $guarded = [];

    public function ipOfficialSelection()
    {
        return $this->belongsTo(IndianPanoramaOfficialSelection::class, 'official_selection_id');
    }
}

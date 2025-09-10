<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JuryDetail extends Model
{
    protected $table = 'jury_details';
    protected $guarded = [];

    public function officialSelection()
    {
        return $this->belongsTo(IndianPanoramaOfficialSelection::class, 'official_selection_id');
    }
}

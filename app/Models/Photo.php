<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'mst_photos';
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(PhotoCategory::class, 'category_id');
    }

}

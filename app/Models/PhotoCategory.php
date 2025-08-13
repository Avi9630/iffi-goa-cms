<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoCategory extends Model
{
    protected $table = 'mst_photos_category';
    protected $guarded = [];

    public function photos()
    {
        return $this->hasMany(Photo::class, 'category_id');
    }
}

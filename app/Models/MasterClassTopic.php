<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterClassTopic extends Model
{
    protected $table = 'master_class_topics';
    protected $guarded = [];

    public function masterDate()
    {
        return $this->belongsTo(MasterClassDate::class, 'master_date_id');
    }
}

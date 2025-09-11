<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    protected $table = 'speakers';
    protected $guarded = [];

    public function masterTopic()
    {
        return $this->belongsTo(MasterClassTopic::class, 'topic_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Moderator extends Model
{
    protected $table = 'moderators';
    protected $guarded = [];

    public function masterTopic()
    {
        return $this->belongsTo(MasterClassTopic::class, 'topic_id');
    }
}

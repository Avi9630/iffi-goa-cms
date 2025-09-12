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

    public function masterClass()
    {
        return $this->hasOne(MasterClass::class, 'topic_id');
    }

    public function moderator()
    {
        return $this->hasOne(Moderator::class, 'topic_id');
    }
}

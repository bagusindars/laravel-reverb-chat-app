<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model
{
    protected $guarded = [];

    public function agents()
    {
        return $this->belongsTo(Agent::class, 'agent_id', 'id');
    }
}

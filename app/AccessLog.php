<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    protected $fillable = ['user_name', 'user_role', 'section',
        'action', 'ip_address', 'location', 'request_method', 'section_id', 'section_name'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

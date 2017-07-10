<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialProfile extends Model
{
    protected $fillable = ['user_id', 'username', 'password'];

    protected $hidden = ['password'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

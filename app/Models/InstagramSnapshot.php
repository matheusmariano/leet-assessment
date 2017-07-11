<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Collectable\Collectable;
use App\Models\Collectable\CollectableTrait;

class InstagramSnapshot extends Model implements Collectable
{
    use CollectableTrait;

    protected $fillable = ['followers', 'following'];
}

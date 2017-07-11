<?php

namespace App\Models\Collectable;

use App\Models\SocialProfile;

trait CollectableTrait {
    public function socialProfile()
    {
        return $this->belongsTo(SocialProfile::class);
    }
}

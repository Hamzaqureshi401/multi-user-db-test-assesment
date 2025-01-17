<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    //
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}

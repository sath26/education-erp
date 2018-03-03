<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    public function user()
    {
        $this->morphOne(User::class, 'entity');
    }
}

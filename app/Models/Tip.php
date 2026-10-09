<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tip extends Model
{
    protected $guarded = ['id'];

    protected $hidden = ['message', 'contact'];

    protected function casts(): array
    {
        return ['message' => 'encrypted', 'contact' => 'encrypted', 'expires_at' => 'datetime'];
    }
}

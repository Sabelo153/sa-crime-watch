<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SponsorInquiry extends Model
{
    protected $guarded = ['id'];

    protected $hidden = ['email', 'message'];

    protected function casts(): array
    {
        return ['email' => 'encrypted', 'message' => 'encrypted', 'expires_at' => 'datetime'];
    }
}

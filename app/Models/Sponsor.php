<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['active' => 'boolean', 'starts_on' => 'date', 'ends_on' => 'date'];
    }

    public function scopePublished($q)
    {
        return $q->where('active', true)->whereDate('starts_on', '<=', today())->whereDate('ends_on', '>=', today());
    }
}

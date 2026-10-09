<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleReport extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['stolen_on' => 'date'];
    }

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function photos()
    {
        return $this->hasMany(ReportPhoto::class);
    }

    public function tips()
    {
        return $this->hasMany(Tip::class);
    }
}

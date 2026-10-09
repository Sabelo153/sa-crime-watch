<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportPhoto extends Model
{
    protected $guarded = ['id'];

    public function report()
    {
        return $this->belongsTo(VehicleReport::class, 'vehicle_report_id');
    }
}

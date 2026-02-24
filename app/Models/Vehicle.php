<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasUuid;

    protected $fillable = [
        'plate_number',
        'gps_device_id',
        'active',
    ];
   
}

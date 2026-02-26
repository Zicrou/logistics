<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasUuid;
    protected $fillable = [
        'full_name',
        'phone',
        'license_number',
        'vehicle_id',
        'last_seen'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
}

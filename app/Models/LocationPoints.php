<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;

class LocationPoints extends Model
{
    use HasUuid;
    protected $fillable = [
        'shipment_id',
        'longitude',
        'latitude',
        'speed'
    ]; 

     public function shipment(){
        return $this->belongsTo(Shipment::class);
    }

    protected $casts = [
    'latitude' => 'float',
    'longitude' => 'float',
];
}

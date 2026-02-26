<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasUuid;

class Transport extends Model
{
    use HasUuid;
    protected $fillable = [
        'shipment_id',
        'mode', 
        'status',
        'departure_date',
        'estimated_arrival',
        'actual_arrival',
    ];

    public function shipment(){
        return $this->belongsTo(Shipment::class);
    }
}

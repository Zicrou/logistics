<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;

class CheckPoint extends Model
{
    use HasUuid;

    protected $fillable = [
        'shipment_id',
        'location',
        'status',
        'passed_at',
        'type'
    ];

     public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}

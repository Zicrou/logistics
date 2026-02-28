<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasUuid;

    protected $fillable = [
        'shipment_id',
        'amount',
        'status',
        'method',
        'currency',
        'paid_at'
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}

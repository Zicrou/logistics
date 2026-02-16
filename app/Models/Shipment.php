<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasUuid;
    protected $fillable = [
        'reference',
        'container_no',
        'cargo_type',
        'origin_port',
        'destination',
        'status',
        'weight',
    ];
    
}

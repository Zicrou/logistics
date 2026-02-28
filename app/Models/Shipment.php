<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Dom\Document;
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
    

    public function documents(){
        return $this->hasMany(Document::class);
    }

    public function transports(){
        return $this->hasMany(Transport::class);
    }

    public function location_points(){
        return $this->hasMany(LocationPoints::class);
    }

    public function checkpoints(){
        return $this->hasMany(CheckPoint::class);
    }

    public function payment(){
        return $this->hasMany(Payment::class);
    }
}

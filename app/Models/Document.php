<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasUuid;
class Document extends Model
{
    use HasUuid;
    protected $fillable = [
        'type',
        'shipment_id',
        'file_url',
        'verified',
        'user_id'
    ];


    public function shipment(){
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}

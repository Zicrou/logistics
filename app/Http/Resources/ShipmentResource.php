<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'container_no' => $this->resource->container_no,
            'cargo_type' => $this->resource->cargo_type,
            'origin_port' => $this->resource->origin_port,
            'destination' => $this->resource->destination,
            'status' => $this->resource->status,
            'weight' => $this->resource->weight,
            
        ];
    }
}

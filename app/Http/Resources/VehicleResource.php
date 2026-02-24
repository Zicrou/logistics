<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
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
            'plate_number' => $this->resource->plate_number,
            'gps_device_id' => $this->resource->gps_device_id,
            'active' => $this->resource->active,
        ];
    }
}

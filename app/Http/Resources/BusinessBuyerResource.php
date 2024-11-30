<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BusinessBuyerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'business_id' => $this->business_id,
            'company_name' => $this->company_name,
            'location' => $this->location,
            'contact_details' => $this->contact_details,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}


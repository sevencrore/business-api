<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BusinessResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array
     */////`bussiness_name`, `address`, `city`, `postal_code`, `phone_number`, `website`, `description`, `keywords`, `updated_at`, `created_at`, `is_approved`
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'business_name' => $this->business_name,
            'address' => $this->address,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'phone_number' => $this->phone_number,
            'website' => $this->website,
            'description' => $this->description,
            'keywords' => $this->keywords,
            'is_approved' => $this->is_approved,
            'contact_person_name'=>$this->contact_person_name,
            'contact_person_email'=>$this->contact_person_email,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }
}

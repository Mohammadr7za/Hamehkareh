<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceListCombo extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category_id' => $this->category_id,
            'subcategory_id' => $this->subcategory_id,
            'provider_id' => $this->provider_id,
            'price' => $this->price,
            'price_format' => getPriceFormat($this->price),
            'type' => $this->type,
            'discount' => $this->discount,
            'duration' => $this->duration,
            'status' => $this->status,
            'description' => $this->description,
            'is_featured' => $this->is_featured,
            'provider_name' => optional($this->providers)->display_name,
            'provider_image' => optional($this->providers)->login_type != null ? optional($this->providers)->social_image : getSingleMedia(optional($this->providers), 'profile_image', null),
            'city_id' => optional($this->providers)->city_id,
            'category_name' => optional($this->category)->name,
            'subcategory_name' => optional($this->subcategory)->name,
            'attchments' => getAttachments($this->getMedia('service_attachment')),
            'total_review' => $this->serviceRating->count('id'),
            'total_rating' => count($this->serviceRating) > 0 ? (float)number_format(max($this->serviceRating->avg('rating'), 0), 2) : 0,
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Models\Booking;
use App\Models\UserFavouriteProvider;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->login_type != null) {
            $profile_image = $this->social_image;
        } else {
            $profile_image = getSingleMedia($this, 'profile_image', null);
        }

        return [

            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'username' => $this->username,
            'provider_id' => $this->provider_id,
            'status' => $this->status,
            'description' => $this->description,
            'user_type' => $this->user_type,
            'email' => $this->email,
            'contact_number' => $this->contact_number,
            'country_id' => $this->country_id,
            'state_id' => $this->state_id,
            'city_id' => $this->city_id,
            'city_name' => optional($this->city)->name,
            'address' => $this->address,
            'providertype_id' => $this->providertype_id,
            'providertype' => optional($this->providertype)->name,
            'is_featured' => $this->is_featured,
            'display_name' => $this->display_name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'profile_image' => $profile_image,
            'time_zone' => $this->time_zone,
            'uid' => $this->uid,
            'login_type' => $this->login_type,
            'service_address_id' => $this->service_address_id,
            'last_notification_seen' => $this->last_notification_seen,
            'isHandymanAvailable' => $this->is_available,
            'designation' => $this->designation,
            'handymantype_id' => $this->handymantype_id,
            'handymantype' => optional($this->handymantype)->name,
            'known_languages' => $this->known_languages,
            'skills' => $this->skills,
            'why_choose_me' => $this->why_choose_me
        ];
    }
}

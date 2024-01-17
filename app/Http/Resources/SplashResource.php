<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SplashResource extends JsonResource
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
            "id" => $this->id,
            "username" => $this->username,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "email" => $this->email,
            "user_type" => (($this->user_type == 'provider') ? 'provider' : (($this->user_type == 'handyman') ? 'handyman' : 'user')),
            "user_type_fa" => (($this->user_type == 'provider') ? 'نمایندگی' : (($this->user_type == 'handyman') ? 'متخصص' : 'کاربر')),
            "contact_number" => $this->contact_number,
            "country_id" => $this->country_id,
            "country_name" => $this->country->name ?? '',
            "state_id" => $this->state_id,
            "state_name" => $this->state->name ?? '',
            "city_id" => $this->city_id,
            "city_name" => $this->city->name ?? '',
            "profile_image" => getSingleMedia($this, 'profile_image', null),
            "provider_id" => $this->provider_id,
            "address" => $this->address,
            "player_id" => $this->player_id,
            "status" => $this->status,
            "display_name" => $this->display_name,
            "providertype_id" => $this->providertype_id,
            "is_featured" => $this->is_featured,
            "time_zone" => $this->time_zone,
            "email_verified_at" => $this->email_verified_at,
            "phone_verifed_at" => $this->email_verified_at,
            "is_phone_verifed" => $this->email_verified_at != null && strlen($this->email_verified_at) > 0,
            "service_address_id" => $this->service_address_id,
            "uid" => $this->uid,
            "handymantype_id" => $this->handymantype_id,
            "is_subscribe" => $this->is_subscribe,
            "social_image" => $this->social_image,
            "is_available" => $this->is_available,
            "designation" => $this->designation,
            "last_online_time" => $this->last_online_time,
            "slots_for_all_services" => $this->slots_for_all_services,
            "known_languages" => $this->known_languages,
            "skills" => $this->skills,
            "description" => $this->description,
            "why_choose_me" => $this->why_choose_me
        ];
    }
}

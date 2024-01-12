<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserDataResource extends JsonResource
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
            "user_type" => $this->user_type,
            "contact_number" => $this->contact_number,
            "country_id" => $this->country_id,
            "state_id" => $this->state_id,
            "city_id" => $this->city_id,
            "provider_id" => $this->provider_id,
            "address" => $this->address,
//            "player_id" => $this->player_id,
            "status" => $this->status,
            "display_name" => $this->display_name,
            "providertype_id" => $this->providertype_id,
            "is_featured" => $this->is_featured,
            "time_zone" => $this->time_zone,
            "last_notification_seen" => $this->last_notification_seen,
//            "email_verified_at" => $this->email_verified_at,
//            "deleted_at" => $this->deleted_at,
//            "created_at" => $this->created_at,
//            "updated_at" => $this->updated_at,
//            "login_type" => $this->login_type,
            "service_address_id" => $this->service_address_id,
//            "is_subscribe" => $this->is_subscribe,
            "social_image" => $this->social_image,
//            "is_available" => $this->is_available,
            "designation" => $this->designation,
            "last_online_time" => $this->last_online_time,
            "slots_for_all_services" => $this->slots_for_all_services,
            "known_languages" => $this->known_languages,
            "skills" => $this->skills,
            "description" => $this->description,
//            "otp_token" => $this->otp_token,
//            "otp_token_expire_time" => $this->otp_token_expire_time,
//            "why_choose_me" => $this->why_choose_me,
//            "uid" => $this->uid,
//            "handymantype_id" => $this->handymantype_id,
        ];
    }
}

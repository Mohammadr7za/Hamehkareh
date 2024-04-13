<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingStatusResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "value" => $this->value,
//            "label" => (__('messages.'.$this->value)),
            "label" => $this->label,
            "status" => $this->status,
            "sequence" => $this->sequence,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}

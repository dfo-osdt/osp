<?php

namespace App\Http\Resources;

use App\Enums\SensitivityLabel;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\User
 */
class UserResource extends JsonResource
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
            'data' => [
                'id' => $this->id,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'locale' => $this->locale,
                'sensitivity_label' => SensitivityLabel::ProtectedA,
                'author' => AuthorResource::make($this->whenLoaded('author')),
            ],
            'can' => [
                'update' => false,
                'delete' => false,
            ],
        ];
    }
}

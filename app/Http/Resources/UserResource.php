<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\SensitivityLabel;
use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|\JsonSerializable
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

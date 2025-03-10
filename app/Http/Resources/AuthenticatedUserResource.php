<?php

namespace App\Http\Resources;

use App\Enums\SensitivityLabel;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\User
 */
class AuthenticatedUserResource extends JsonResource
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
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'locale' => $this->locale,
            'sensitivity_label' => SensitivityLabel::ProtectedA,
            'new_password_required' => $this->new_password_required,
            'author' => AuthorResource::make($this->author),
            'roles' => $this->getRoleNames(),
            'last_login_at' => $this->previousSuccessfulLoginAt(),
            'permissions' => $this->getAllPermissions()->pluck('name'),
        ];
    }
}

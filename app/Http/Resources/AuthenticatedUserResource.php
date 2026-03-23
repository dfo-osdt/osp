<?php

namespace App\Http\Resources;

use App\Enums\SensitivityLabel;
use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class AuthenticatedUserResource extends JsonResource
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
            'is_acting_as_delegate' => $this->isActingAsDelegate(),
        ];
    }
}

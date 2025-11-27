<?php

namespace App\Http\Resources;

use App\Enums\SensitivityLabel;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/**
 * Resource for Author model.
 *
 * @mixin \App\Models\Author
 */
class AuthorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'data' => [
                'id' => $this->id,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'orcid' => $this->orcid ?? '',
                'orcid_verified' => $this->orcid_verified ?? false,
                'orcid_connected' => (bool) $this->orcid_access_token,
                'email' => $this->email,
                'user_id' => $this->user_id,
                'organization_id' => $this->organization_id,
                'sensitivity_label' => SensitivityLabel::ProtectedA,
                'organization' => OrganizationResource::make($this->whenLoaded('organization')),
                'expertises' => ExpertiseResource::collection($this->whenLoaded('expertises')),
                'publications' => PublicationResource::collection($this->whenLoaded('publications')),
            ],
            'can' => [
                'update' => Auth::user()->can('update', $this->resource),
                'delete' => Auth::user()->can('delete', $this->resource),
            ],
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A slim author resource for unauthenticated contexts.
 * Excludes email, user_id, and permission checks.
 *
 * @mixin \App\Models\Author
 */
class PublicAuthorResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'orcid' => $this->orcid ?? '',
            'orcid_verified' => $this->orcid_verified ?? false,
        ];
    }
}

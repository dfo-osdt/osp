<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/**
 * @mixin \App\Models\AuthorEmployment
 */
class AuthorEmploymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => [
                'id' => $this->id,
                'author_id' => $this->author_id,
                'organization_id' => $this->organization_id,
                'role_title' => $this->role_title,
                'department_name' => $this->department_name,
                'start_date' => $this->start_date->format('Y-m-d'),
                'end_date' => $this->end_date?->format('Y-m-d'),
                'organization' => OrganizationResource::make($this->whenLoaded('organization')),
                'author' => AuthorResource::make($this->whenLoaded('author')),
                'orcid_synced' => $this->isSyncedWithOrcid(),
                'orcid_needs_sync' => $this->needsSyncWithOrcid(),
                'orcid_last_synched_at' => $this->orcid_updated_at,
            ],
            'can' => [
                'update' => Auth::user()->can('update', $this->resource),
                'delete' => Auth::user()->can('delete', $this->resource),
            ],
        ];
    }
}

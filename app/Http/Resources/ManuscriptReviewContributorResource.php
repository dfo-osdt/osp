<?php

namespace App\Http\Resources;

use App\Models\ManuscriptReviewContributor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/**
 * @mixin ManuscriptReviewContributor
 */
class ManuscriptReviewContributorResource extends JsonResource
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
                'manuscript_record_id' => $this->manuscript_record_id,
                'author_id' => $this->author_id,
                'role' => $this->role,
                'author' => AuthorResource::make($this->whenLoaded('author')),
            ],
            'can' => [
                'update' => Auth::user()->can('update', $this->resource),
                'delete' => Auth::user()->can('delete', $this->resource),
            ],
        ];
    }
}

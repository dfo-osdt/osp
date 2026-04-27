<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\HelpfulLink;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin HelpfulLink
 */
class HelpfulLinkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order' => $this->order,
            'title_en' => $this->title_en,
            'title_fr' => $this->title_fr,
            'description_en' => $this->description_en,
            'description_fr' => $this->description_fr,
            'url_en' => $this->url_en,
            'url_fr' => $this->url_fr,
        ];
    }
}

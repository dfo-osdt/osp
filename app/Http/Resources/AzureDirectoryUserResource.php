<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Microsoft\Graph\Generated\Models\User;

/**
 * AzureDirectoryUserResource used to transform the user object from the Azure
 * Directory into a JSON response.
 *
 * @mixin User
 */
class AzureDirectoryUserResource extends JsonResource
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
                'id' => $this->getId(),
                'display_name' => $this->getDisplayName(),
                'first_name' => $this->getGivenName(),
                'last_name' => $this->getSurname(),
                'job_title' => $this->getJobTitle(),
                'email' => $this->getMail(),
                'locale' => $this->getPreferredLanguage() === 'fr-CA' ? 'fr' : 'en',
            ],
        ];
    }
}

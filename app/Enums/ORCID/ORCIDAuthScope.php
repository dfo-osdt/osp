<?php

namespace App\Enums\ORCID;

use InvalidArgumentException;

/**
 * Describes the scope of access to an ORCID OAuth token.
 */
enum ORCIDAuthScope: string
{
    case READ_LIMITED = '/read-limited';
    case ACTIVITIES_UPDATE = '/activities/update';
    case PERSON_UPDATE = '/person/update';

    public static function fromString(string $value): ORCIDAuthScope
    {
        return match ($value) {
            '/read-limited' => self::READ_LIMITED,
            '/activities/update' => self::ACTIVITIES_UPDATE,
            '/person/update' => self::PERSON_UPDATE,
            default => throw new InvalidArgumentException("Invalid ORCIDAuthScope value: $value"),
        };
    }

    public function translate(?string $locale = null): string
    {
        collect(['en', 'fr'])->contains($locale) ?: $locale = null;
        if ($locale == null) {
            $locale = app()->getLocale();
        }

        return match ($locale) {
            'en' => match ($this->value) {
                '/read-limited' => 'Read limited',
                '/activities/update' => 'Activities update',
                '/person/update' => 'Person update',
            },
            'fr' => match ($this->value) {
                '/read-limited' => 'Lecture limitée',
                '/activities/update' => 'Mise à jour des activités',
                '/person/update' => 'Mise à jour de la personne',
            },
            default => $this->value,
        };
    }

    /**
     * Encode an array of ORCIDAuth for use in a query string for ORCID API,
     * note that is URL encoded with %20 instead of a space.
     *
     * @param  ORCIDAuthScope[]  $scopes
     */
    public static function encode(array $scopes): string
    {
        $scopes = array_map(fn ($scope) => $scope->value, $scopes);

        return implode('%20', $scopes);
    }

    /**
     * Get a "complete access" scope for use in a query string for ORCID API,
     * note that this is a URL encoded string with %20 isntead of space.
     */
    public static function completeAccess(): string
    {
        return self::encode([
            self::READ_LIMITED,
            self::ACTIVITIES_UPDATE,
            self::PERSON_UPDATE,
        ]);
    }

    /**
     * Decode a string of scopes from a query string from ORCID API and return
     * an array of ORCIDAuthScope objects. Scopes are separated by a space.
     *
     * @return ORCIDAuthScope[]
     */
    public static function decodeScopes(string $scopes): array
    {
        $scopes = explode(' ', $scopes);

        return array_map(fn ($scope) => self::fromString($scope), $scopes);
    }
}

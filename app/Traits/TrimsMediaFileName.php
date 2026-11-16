<?php

namespace App\Traits;

use Illuminate\Database\Schema\Builder;

trait TrimsMediaFileName
{
    /**
     * Trim the file name to fit the media name and file_name columns, which use
     * the default string length. Counting bytes rather than characters also keeps
     * it under the filesystem limit, and mb_strcut never splits a multibyte
     * character.
     */
    protected function trimFileName(string $fileName): string
    {
        $maxLength = Builder::$defaultStringLength;

        if (strlen($fileName) <= $maxLength) {
            return $fileName;
        }

        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $base = pathinfo($fileName, PATHINFO_FILENAME);
        $suffix = $ext !== '' ? '.'.$ext : '';

        return mb_strcut($base, 0, $maxLength - strlen($suffix)).$suffix;
    }
}

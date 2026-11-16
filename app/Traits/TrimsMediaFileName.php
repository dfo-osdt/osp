<?php

namespace App\Traits;

trait TrimsMediaFileName
{
    /**
     * Trim the file name to 255 bytes, the filesystem limit, keeping the
     * extension and never splitting a multibyte character.
     */
    protected function trimFileName(string $fileName): string
    {
        if (strlen($fileName) <= 255) {
            return $fileName;
        }

        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $base = pathinfo($fileName, PATHINFO_FILENAME);
        $suffix = $ext !== '' ? '.'.$ext : '';

        return mb_strcut($base, 0, 255 - strlen($suffix)).$suffix;
    }
}

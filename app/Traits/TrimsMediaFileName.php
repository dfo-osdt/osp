<?php

namespace App\Traits;

trait TrimsMediaFileName
{
    protected function trimFileName(string $fileName): string
    {
        if (mb_strlen($fileName) <= 255) {
            return $fileName;
        }

        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $base = pathinfo($fileName, PATHINFO_FILENAME);
        $suffix = $ext !== '' ? '.'.$ext : '';

        return mb_substr($base, 0, 255 - mb_strlen($suffix)).$suffix;
    }
}

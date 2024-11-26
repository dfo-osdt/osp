<?php

namespace App\Enums;

enum MediaCollection: string
{
    case MANUSCRIPT = 'manuscript';
    case SUPPLEMENTARY_FILE = 'supplementary_file';
    case PUBLICATION = 'publication';
}
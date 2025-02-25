<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Enumeration for user roles
 */
enum ImageCategory: string
{
    case EVENT = 'event';
    case DATE = 'date';
}

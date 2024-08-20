<?php
declare(strict_types=1);

namespace App\Enum;

enum ActivityType: string {
    case RUNNING = 'RUNNING';
    case CYCLING = 'CYCLING';
    case SWIMMING = 'SWIMMING';
    case WALKING = 'WALKING';
    case HIKING = 'HIKING';
}

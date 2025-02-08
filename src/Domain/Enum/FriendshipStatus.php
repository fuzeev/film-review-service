<?php

declare(strict_types=1);

namespace App\Domain\Enum;

enum FriendshipStatus: string
{
    case REQUEST_SENT = 'request_sent';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case CANCELLED = 'cancelled';
}

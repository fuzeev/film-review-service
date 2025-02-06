<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Enum\FriendshipStatus;
use DateTimeImmutable;

class Friendship
{
    public function __construct(
        public string $id,
        public User $initiator,
        public User $acceptor,
        public FriendshipStatus $status,
        public DateTimeImmutable $requestCreatedAt,
        public ?DateTimeImmutable $startedAt,
        public ?DateTimeImmutable $endedAt,
    ) {}
}
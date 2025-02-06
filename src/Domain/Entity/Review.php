<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Exception\InvalidRatingException;

readonly class Review
{
    public function __construct(
        public int $id,
        public User $author,
        public string $title,
        public string $text,
        public int $rating,
    ) {
        if ($rating < 1 || $rating > 10) {
            throw new InvalidRatingException($rating);
        }
    }
}
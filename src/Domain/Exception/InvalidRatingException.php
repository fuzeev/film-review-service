<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class InvalidRatingException extends DomainException
{
    public function __construct(int $incorrectRating)
    {
        parent::__construct('Некорректный рейтинг: ' . $incorrectRating);
    }
}

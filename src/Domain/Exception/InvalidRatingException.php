<?php
declare(strict_types=1);

namespace App\Domain\Exception;

use Exception;

class InvalidRatingException extends Exception
{
    public function __construct(int $incorrectRating)
    {
        parent::__construct('Некорректный рейтинг: ' . $incorrectRating);
    }
}
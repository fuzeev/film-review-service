<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class CannotAddMovieException extends DomainException
{
    public function __construct(?string $message = null)
    {
        $errMessage = 'Не удалось добавить фильм.';
        if ($message !== null) {
            $errMessage .= " $message";
        }

        parent::__construct($errMessage);
    }
}

<?php

namespace App\Infrastructure\Storage\Exception;

use App\Domain\Exception\PersistenceException;

class MoviePersistenceException extends PersistenceException
{
    public function __construct()
    {
        parent::__construct("Неизвестная ошибка. Не удалось сохранить фильм");
    }
}

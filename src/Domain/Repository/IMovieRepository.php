<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Dto\AddMovieDto;
use App\Domain\Entity\Movie;

interface IMovieRepository extends IEntityRepository
{
    /**
     * Возвращает сущность фильма, либо null, если фильм с таким id не существует
     */
    public function getById(int $id): ?Movie;

    /**
     * Создает сущность фильма. Если удалось, возвращает доменную модель.
     * Если не удалось, бросает исключение.
     */
    public function add(AddMovieDto $dto): Movie;
}

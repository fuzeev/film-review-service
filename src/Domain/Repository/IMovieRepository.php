<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Dto\AddMovieDto;
use App\Domain\Dto\GetMovieListQuery;
use App\Domain\Dto\GetMovieListResult;
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

    /**
     * Возвращает список фильмов в соответствии с фильтрами, примененными в объекте запроса
     *
     * @param GetMovieListQuery $dto
     * @return GetMovieListResult
     */
    public function getList(GetMovieListQuery $dto): GetMovieListResult;
}

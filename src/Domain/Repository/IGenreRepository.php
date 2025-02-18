<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Genre;

interface IGenreRepository extends IEntityRepository
{
    /**
     * Возвращает сущность жанра, либо null, если жанр с таким id не существует
     *
     * @param int $id
     * @return Genre|null
     */
    public function getById(int $id): ?Genre;
}

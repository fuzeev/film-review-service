<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Director;

interface IDirectorRepository extends IEntityRepository
{
    /**
     * Возвращает сущность режиссера, либо null, если режиссер с таким id не существует
     */
    public function getById(int $id): ?Director;
}

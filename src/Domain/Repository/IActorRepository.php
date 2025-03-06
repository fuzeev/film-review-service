<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Actor;

interface IActorRepository extends IEntityRepository
{
    /**
     * Возвращает сущность актера, либо null, если актер с таким id не существует
     */
    public function getById(int $id): ?Actor;
}

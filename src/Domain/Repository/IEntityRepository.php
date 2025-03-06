<?php

declare(strict_types=1);

namespace App\Domain\Repository;

interface IEntityRepository
{
    /**
     * Проверяет, существует ли сущность с указанным id
     */
    public function checkIdExists(int $id): bool;

    /**
     * Возвращает список несуществующих id из переданных.
     * Позволяет за раз проверить несколько id, если возвращает пустой массив -
     * все id существуют в базе.
     *
     * @param int[] $idsToCheck
     */
    public function findNonExistentIds(array $idsToCheck): array;
}

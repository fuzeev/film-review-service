<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Country;

interface ICountryRepository extends IEntityRepository
{
    /**
     * Возвращает сущность страны, либо null, если страна с таким id не существует
     */
    public function getById(int $id): ?Country;
}

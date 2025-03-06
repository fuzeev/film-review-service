<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\Repository;

trait EntityRepositoryTrait
{
    public function checkIdExists(int $id): bool
    {
        return $this->find($id) !== null;
    }

    public function findNonExistentIds(array $idsToCheck): array
    {
        $qb = $this->createQueryBuilder('e');
        $existingIds = $qb->select('e.id')
            ->where($qb->expr()->in('e.id', ':ids'))
            ->setParameter('ids', $idsToCheck)
            ->getQuery()
            ->getSingleColumnResult();

        return array_values(array_diff($idsToCheck, $existingIds));
    }
}

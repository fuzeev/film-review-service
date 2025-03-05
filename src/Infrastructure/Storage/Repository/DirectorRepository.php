<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\Repository;

use App\Domain\Repository\IDirectorRepository;
use App\Infrastructure\Storage\Converter\DirectorConverter;
use App\Infrastructure\Storage\Entity\Director as DoctrineDirector;
use App\Domain\Entity\Director as DomainDirector;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Director>
 */
class DirectorRepository extends ServiceEntityRepository implements IDirectorRepository
{
    use EntityRepositoryTrait;

    public function __construct(
        ManagerRegistry $registry,
        protected DirectorConverter $converter,
    )
    {
        parent::__construct($registry, DoctrineDirector::class);
    }

    public function getById(int $id): ?DomainDirector
    {
        $model = $this->find($id);
        return $this->converter->doctrineToDomain($model);
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\Repository;

use App\Domain\Repository\IActorRepository;
use App\Infrastructure\Storage\Converter\ActorConverter;
use App\Infrastructure\Storage\Entity\Actor as DoctrineActor;
use App\Domain\Entity\Actor as DomainActor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Actor>
 */
class ActorRepository extends ServiceEntityRepository implements IActorRepository
{
    use EntityRepositoryTrait;

    public function __construct(
        ManagerRegistry $registry,
        protected ActorConverter $converter,
    )    {
        parent::__construct($registry, DoctrineActor::class);
    }

    public function getById(int $id): ?DomainActor
    {
        $model = $this->find($id);
        return $this->converter->doctrineToDomain($model);
    }
}

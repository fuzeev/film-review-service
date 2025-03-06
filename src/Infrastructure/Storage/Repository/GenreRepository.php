<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\Repository;

use App\Domain\Entity\Genre as DomainGenre;
use App\Domain\Repository\IGenreRepository;
use App\Infrastructure\Storage\Converter\GenreConverter;
use App\Infrastructure\Storage\Entity\Genre as DoctrineGenre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DoctrineGenre>
 */
class GenreRepository extends ServiceEntityRepository implements IGenreRepository
{
    use EntityRepositoryTrait;

    public function __construct(
        ManagerRegistry $registry,
        protected GenreConverter $converter,
    ) {
        parent::__construct($registry, DoctrineGenre::class);
    }

    public function getById(int $id): ?DomainGenre
    {
        $model = $this->find($id);

        return $this->converter->doctrineToDomain($model);
    }
}

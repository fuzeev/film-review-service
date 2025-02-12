<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\Repository;

use App\Domain\Entity\Movie as DomainMovie;
use App\Domain\Repository\IMovieRepository;
use App\Infrastructure\Storage\Converter\MovieConverter;
use App\Infrastructure\Storage\Entity\Movie as DoctrineMovie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DoctrineMovie>
 */
class MovieRepository extends ServiceEntityRepository implements IMovieRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private MovieConverter $converter,
    ) {
        parent::__construct($registry, DoctrineMovie::class);
    }

    public function getById(int $id): ?DomainMovie
    {
        /** @var ?DoctrineMovie $movie */
        $movie = $this->find($id);

        if ($movie === null) {
            return null;
        }

        return $this->converter->doctrineToDomain($movie);
    }
}

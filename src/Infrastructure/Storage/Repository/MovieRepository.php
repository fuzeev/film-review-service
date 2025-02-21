<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\Repository;

use App\Domain\Dto\AddMovieDto;
use App\Domain\Entity\Movie as DomainMovie;
use App\Domain\Repository\IMovieRepository;
use App\Infrastructure\Storage\Converter\MovieConverter;
use App\Infrastructure\Storage\Entity\Movie as DoctrineMovie;
use App\Infrastructure\Storage\Entity\Actor as DoctrineActor;
use App\Infrastructure\Storage\Entity\Genre as DoctrineGenre;
use App\Infrastructure\Storage\Entity\Country as DoctrineCountry;
use App\Infrastructure\Storage\Entity\Director as DoctrineDirector;
use App\Infrastructure\Storage\Exception\MoviePersistenceException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @extends ServiceEntityRepository<DoctrineMovie>
 */
class MovieRepository extends ServiceEntityRepository implements IMovieRepository
{
    use EntityRepositoryTrait;

    public function __construct(
        ManagerRegistry $registry,
        readonly private MovieConverter $converter,
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

    public function add(AddMovieDto $dto): DomainMovie
    {
        $movie = new DoctrineMovie();
        $movie->setTitle($dto->title);
        $movie->setDescription($dto->description);
        $movie->setTitleOriginal($dto->titleOriginal);
        $movie->setYear($dto->year);
        foreach ($dto->actorIds as $actorId) {
            $movie->addActor($this->getEntityManager()->getReference(DoctrineActor::class, $actorId));
        }
        foreach ($dto->genreIds as $genreId) {
            $movie->addGenre($this->getEntityManager()->getReference(DoctrineGenre::class, $genreId));
        }
        $movie->setDirector($this->getEntityManager()->getReference(DoctrineDirector::class, $dto->directorId));
        $movie->setCountry($this->getEntityManager()->getReference(DoctrineCountry::class, $dto->countryId));

        $this->getEntityManager()->persist($movie);

        try {
            $this->getEntityManager()->flush();
            return $this->converter->doctrineToDomain($movie);
        } catch (Exception $e) {
            //Log::error($e->getMessage()); //после добавления логирования здесь будет логироваться ошибка
            throw new MoviePersistenceException;
        }
    }
}

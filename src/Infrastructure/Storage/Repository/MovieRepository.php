<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\Repository;

use App\Domain\Dto\AddMovieDto;
use App\Domain\Dto\GetMovieListQuery;
use App\Domain\Dto\GetMovieListResult;
use App\Domain\Entity\Movie as DomainMovie;
use App\Domain\Repository\IMovieRepository;
use App\Infrastructure\Storage\Converter\MovieConverter;
use App\Infrastructure\Storage\Entity\Actor as DoctrineActor;
use App\Infrastructure\Storage\Entity\Country as DoctrineCountry;
use App\Infrastructure\Storage\Entity\Director as DoctrineDirector;
use App\Infrastructure\Storage\Entity\Genre as DoctrineGenre;
use App\Infrastructure\Storage\Entity\Movie as DoctrineMovie;
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
        $movie->setSource($dto->source);
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

        $this->getEntityManager()
            ->persist($movie);

        try {
            $this->getEntityManager()
                ->flush();

            return $this->converter->doctrineToDomain($movie);
        } catch (Exception $e) {
            //Log::error($e->getMessage()); //после добавления логирования здесь будет логироваться ошибка
            throw new MoviePersistenceException();
        }
    }

    public function getList(GetMovieListQuery $dto): GetMovieListResult
    {
        $query = $this->getEntityManager()->createQueryBuilder();
        $query->select('m')
            ->from(DoctrineMovie::class, 'm');

        if ($dto->title) {
            $query->andWhere('movie.title LIKE :title')
                ->setParameter('title', '%' . $dto->title . '%');
        }

        if ($dto->titleOriginal) {
            $query->andWhere('m.titleOriginal LIKE :titleOriginal')
                ->setParameter('titleOriginal', '%' . $dto->titleOriginal . '%');
        }

        if ($dto->yearStart) {
            $query->andWhere('m.year >= :yearStart')
                ->setParameter('yearStart', $dto->yearStart);
        }

        if ($dto->yearEnd) {
            $query->andWhere('m.year <= :yearEnd')
                ->setParameter('yearEnd', $dto->yearEnd);
        }

        if ($dto->actorId) {
            $query->join('m.actors', 'a')
                ->andWhere('a.id = :actorId')
                ->setParameter('actorId', $dto->actorId);
        }

        if ($dto->genreId) {
            $query->join('m.genres', 'g')
                ->andWhere('g.id = :genreId')
                ->setParameter('genreId', $dto->genreId);
        }

        if ($dto->ratingMin) {
            $query->andWhere('m.rating >= :ratingMin')
                ->setParameter('ratingMin', $dto->ratingMin);
        }

        if ($dto->directorId) {
            $query->andWhere('m.director_id = :directorId')
                ->setParameter('directorId', $dto->directorId);
        }

        if ($dto->countryId) {
            $query->andWhere('m.country_id = :countryId')
                ->setParameter('countryId', $dto->countryId);
        }

        // Подсчет общего количества записей (без пагинации)
        $totalCountQuery = (clone $query)
            ->select('COUNT(m.id)')
            ->getQuery();

        // Сортировка
        if ($dto->sortBy) {
            $sortType = $dto->sortType->value;
            $query->orderBy('m.' . $dto->sortBy->value, $sortType);
        }

        // Пагинация
        if ($dto->limit) {
            $query->setMaxResults($dto->limit);
        }

        if ($dto->offset) {
            $query->setFirstResult($dto->offset);
        }

        // Получаем фильмы
        $movies = $query->getQuery()->getResult();
        $movies = array_map(fn ($movie) => $this->converter->doctrineToDomain($movie), $movies);

        return new GetMovieListResult(
            movies: $movies,
            limit: $dto->limit,
            offset: $dto->offset,
            totalCount: $totalCountQuery->getSingleScalarResult(),
            sortBy: $dto->sortBy,
            sortType: $dto->sortType,
        );
    }
}

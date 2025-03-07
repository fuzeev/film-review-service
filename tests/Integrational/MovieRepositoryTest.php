<?php

declare(strict_types=1);

namespace App\Tests\Integrational;

use App\Domain\Dto\AddMovieDto;
use App\Domain\Entity\Actor;
use App\Domain\Entity\Genre;
use App\Domain\Entity\Movie as DomainMovie;
use App\Domain\Enum\MovieSource;
use App\Domain\Exception\PersistenceException;
use App\Infrastructure\Storage\Entity\Actor as DoctrineActor;
use App\Infrastructure\Storage\Entity\Genre as DoctrineGenre;
use App\Infrastructure\Storage\Entity\Movie as DoctrineMovie;
use App\Infrastructure\Storage\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MovieRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    private MovieRepository $movieRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get(EntityManagerInterface::class);
        /** @var MovieRepository $movieRepository */
        $movieRepository = $container->get(MovieRepository::class);

        $this->entityManager = $entityManager;
        $this->movieRepository = $movieRepository;
    }

    protected function getTestMovie(): ?DoctrineMovie
    {
        return $this->entityManager
            ->getRepository(DoctrineMovie::class)
            ->findOneBy([
                'title_original' => 'Scarface',
            ]);
    }

    /**
     * Возвращает несуществующий id для заданной сущности (максимальный id + 1)
     *
     * @param class-string $entityClass
     * @return int
     */
    protected function getNonExistingId(string $entityClass): int
    {
        $maxId = (int) $this->entityManager->createQueryBuilder()
            ->select('MAX(e.id)')
            ->from($entityClass, 'e')
            ->getQuery()
            ->getSingleScalarResult();

        return $maxId + 1;
    }

    public function testFindByIdExisting(): void
    {
        $movie = $this->getTestMovie();
        if ($movie === null) {
            throw new Exception('Фикстуры не загружены');
        }

        $id = $movie->getId();
        if ($id === null) {
            throw new Exception('Фикстуры не загружены');
        }

        $domainMovie = $this->movieRepository->getById($id);

        $this->assertInstanceOf(DomainMovie::class, $domainMovie);
        $this->assertEquals('Лицо со шрамом', $domainMovie->title);
        $this->assertEquals('Scarface', $domainMovie->titleOriginal);
        $this->assertEquals($movie->getId(), $domainMovie->id);
        $this->assertEquals(MovieSource::MANUAL, $domainMovie->source);
        $this->assertEquals(1985, $domainMovie->year);
        $this->assertEquals(8.9, $domainMovie->rating);

        $this->assertEquals(2, count($domainMovie->genres));
        $genreNames = array_map(fn (Genre $genre) => $genre->name, $domainMovie->genres);
        $this->assertContains('Криминал', $genreNames);
        $this->assertContains('Драма', $genreNames);

        $this->assertEquals(1, count($domainMovie->actors));
        $actorNames = array_map(fn (Actor $actor) => $actor->getFullName(), $domainMovie->actors);
        $this->assertContains('Аль Пачино', $actorNames);
    }

    public function testFindByIdNonExisting(): void
    {
        $result = $this->movieRepository->getById($this->getNonExistingId(DoctrineMovie::class));

        $this->assertNull($result);
    }

    public function testFindByIdInvalid(): void
    {
        foreach ([-1, 0] as $id) {
            $result = $this->movieRepository->getById($id);

            $this->assertNull($result);
        }
    }

    public function testAddMovie()
    {
        //берем какой то имеющийся в системе фильм чтобы с него скопировать id зависимостей
        $movie = $this->getTestMovie();
        if ($movie === null) {
            throw new Exception('Фикстуры не загружены');
        }

        $genreIds = $movie->getGenres()
            ->map(fn (DoctrineGenre $genre) => $genre->getId())
            ->toArray();
        $actorIds = $movie->getActors()
            ->map(fn (DoctrineActor $actor) => $actor->getId())
            ->toArray();
        $director = $movie->getDirector()?->getId();
        $country = $movie->getCountry()?->getId();

        if (! $genreIds || ! $actorIds || ! $director || ! $country) {
            throw new Exception('Фикстуры не загружены');
        }

        $name = 'Тестовый фильм (добавление)';
        $desc = 'Тестовое описание фильма';
        $titleOriginal = 'Test';
        $year = 2025;
        $source = MovieSource::MANUAL;

        $dto = new AddMovieDto(
            source: $source,
            title: $name,
            description: $desc,
            titleOriginal: $titleOriginal,
            year: $year,
            genreIds: $genreIds,
            directorId: $director,
            actorIds: $actorIds,
            countryId: $country,
        );

        $insertedMovie = $this->movieRepository->add($dto);

        $this->assertInstanceOf(DomainMovie::class, $insertedMovie);
        $this->assertGreaterThan(0, $insertedMovie->id);
        $this->assertEquals($name, $insertedMovie->title);
        $this->assertEquals($desc, $insertedMovie->description);
        $this->assertEquals($titleOriginal, $insertedMovie->titleOriginal);
        $this->assertEquals($year, $insertedMovie->year);
        $this->assertEquals($source, $insertedMovie->source);
        $this->assertEquals($director, $insertedMovie->director->id);
        $this->assertEquals($country, $insertedMovie->country->id);
        $this->assertEquals(count($genreIds), count($insertedMovie->genres));
        $this->assertEquals(count($actorIds), count($insertedMovie->actors));
    }

    /**
     * Тест добавления фильма с невалидным id жанра
     */
    public function testAddMovieWithInvalidGenre(): void
    {
        $movie = $this->getTestMovie();
        if ($movie === null) {
            throw new Exception('Фикстуры не загружены');
        }

        $genreIds = $movie->getGenres()
            ->map(fn (DoctrineGenre $genre) => $genre->getId())
            ->toArray();
        $actorIds = $movie->getActors()
            ->map(fn (DoctrineActor $actor) => $actor->getId())
            ->toArray();
        $director = $movie->getDirector()?->getId();
        $country = $movie->getCountry()?->getId();

        if (! $genreIds || ! $actorIds || ! $director || ! $country) {
            throw new Exception('Фикстуры не загружены');
        }

        // Заменяем один из id жанров на несуществующий
        $invalidGenreId = $this->getNonExistingId(DoctrineGenre::class);
        $genreIds[0] = $invalidGenreId;

        $dto = new AddMovieDto(
            source: MovieSource::MANUAL,
            title: 'Тестовый фильм (невалидный жанр)',
            description: 'Описание фильма',
            titleOriginal: 'Test',
            year: 2025,
            genreIds: $genreIds,
            directorId: $director,
            actorIds: $actorIds,
            countryId: $country,
        );

        $this->expectException(PersistenceException::class);
        $this->movieRepository->add($dto);
    }

    /**
     * Тест добавления фильма с невалидным id режиссёра
     */
    public function testAddMovieWithInvalidDirector(): void
    {
        $movie = $this->getTestMovie();
        if ($movie === null) {
            throw new Exception('Фикстуры не загружены');
        }

        $genreIds = $movie->getGenres()
            ->map(fn (DoctrineGenre $genre) => $genre->getId())
            ->toArray();
        $actorIds = $movie->getActors()
            ->map(fn (DoctrineActor $actor) => $actor->getId())
            ->toArray();
        $director = $movie->getDirector();
        $country = $movie->getCountry()?->getId();

        if (! $genreIds || ! $actorIds || ! $director || ! $country) {
            throw new Exception('Фикстуры не загружены');
        }

        // Устанавливаем невалидный id режиссёра
        $invalidDirectorId = $this->getNonExistingId(get_class($director));

        $dto = new AddMovieDto(
            source: MovieSource::MANUAL,
            title: 'Тестовый фильм (невалидный режиссёр)',
            description: 'Описание фильма',
            titleOriginal: 'Test',
            year: 2025,
            genreIds: $genreIds,
            directorId: $invalidDirectorId,
            actorIds: $actorIds,
            countryId: $country,
        );

        $this->expectException(PersistenceException::class);
        $this->movieRepository->add($dto);
    }

    /**
     * Тест добавления фильма с невалидным id актёра
     */
    public function testAddMovieWithInvalidActor(): void
    {
        $movie = $this->getTestMovie();
        if ($movie === null) {
            throw new Exception('Фикстуры не загружены');
        }

        $genreIds = $movie->getGenres()
            ->map(fn (DoctrineGenre $genre) => $genre->getId())
            ->toArray();
        $actorIds = $movie->getActors()
            ->map(fn (DoctrineActor $actor) => $actor->getId())
            ->toArray();
        $director = $movie->getDirector()?->getId();
        $country = $movie->getCountry()?->getId();

        if (! $genreIds || ! $actorIds || ! $director || ! $country) {
            throw new Exception('Фикстуры не загружены');
        }

        // Заменяем один из id актёров на несуществующий
        $invalidActorId = $this->getNonExistingId(DoctrineActor::class);
        $actorIds[0] = $invalidActorId;

        $dto = new AddMovieDto(
            source: MovieSource::MANUAL,
            title: 'Тестовый фильм (невалидный актёр)',
            description: 'Описание фильма',
            titleOriginal: 'Test',
            year: 2025,
            genreIds: $genreIds,
            directorId: $director,
            actorIds: $actorIds,
            countryId: $country,
        );

        $this->expectException(PersistenceException::class);
        $this->movieRepository->add($dto);
    }

    /**
     * Тест добавления фильма с невалидным id страны
     */
    public function testAddMovieWithInvalidCountry(): void
    {
        $movie = $this->getTestMovie();
        if ($movie === null) {
            throw new Exception('Фикстуры не загружены');
        }

        $genreIds = $movie->getGenres()
            ->map(fn (DoctrineGenre $genre) => $genre->getId())
            ->toArray();
        $actorIds = $movie->getActors()
            ->map(fn (DoctrineActor $actor) => $actor->getId())
            ->toArray();
        $director = $movie->getDirector()?->getId();
        $country = $movie->getCountry();

        if (! $genreIds || ! $actorIds || ! $director || ! $country) {
            throw new Exception('Фикстуры не загружены');
        }

        // Устанавливаем невалидный id страны
        $invalidCountryId = $this->getNonExistingId(get_class($country));

        $dto = new AddMovieDto(
            source: MovieSource::MANUAL,
            title: 'Тестовый фильм (невалидная страна)',
            description: 'Описание фильма',
            titleOriginal: 'Test',
            year: 2025,
            genreIds: $genreIds,
            directorId: $director,
            actorIds: $actorIds,
            countryId: $invalidCountryId,
        );

        $this->expectException(PersistenceException::class);
        $this->movieRepository->add($dto);
    }
}

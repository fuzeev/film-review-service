<?php

declare(strict_types=1);

namespace App\Tests\Integrational;

use App\Domain\Dto\AddMovieDto;
use App\Domain\Entity\Actor;
use App\Domain\Entity\Genre;
use App\Domain\Entity\Movie as DomainMovie;
use App\Domain\Enum\MovieSource;
use App\Infrastructure\Storage\Entity\Movie as DoctrineMovie;
use App\Infrastructure\Storage\Entity\Genre as DoctrineGenre;
use App\Infrastructure\Storage\Entity\Actor as DoctrineActor;
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

        $domainMovie = $this->movieRepository->getById($movie->getId());

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
        $maxId = (int) $this->entityManager->createQueryBuilder()
            ->select('MAX(m.id)')
            ->from(DoctrineMovie::class, 'm')
            ->getQuery()
            ->getSingleScalarResult();

        $nonExistingId = $maxId + 1;
        $result = $this->movieRepository->getById($nonExistingId);

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

        $genreIds = $movie->getGenres()?->map(fn (DoctrineGenre $genre) => $genre->getId())->toArray();
        $actorIds = $movie->getActors()?->map(fn (DoctrineActor $actor) => $actor->getId())->toArray();
        $director = $movie->getDirector()?->getId();
        $country = $movie->getCountry()?->getId();

        if (!$genreIds || !$actorIds || !$director || !$country) {
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
        $this->assertNotNull($insertedMovie->id);
        $this->assertGreaterThan(0, $insertedMovie->id);
        $this->assertEquals($name, $insertedMovie->title);
        $this->assertEquals($desc, $insertedMovie->description);
        $this->assertEquals($titleOriginal, $insertedMovie->titleOriginal);
        $this->assertEquals($year, $insertedMovie->year);
        $this->assertEquals($source, $insertedMovie->source);
        $this->assertEquals($director, $insertedMovie->director?->id);
        $this->assertEquals($country, $insertedMovie->country?->id);
        $this->assertEquals(count($genreIds), count($insertedMovie->genres));
        $this->assertEquals(count($actorIds), count($insertedMovie->actors));
    }
}

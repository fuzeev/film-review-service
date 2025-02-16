<?php

declare(strict_types=1);

namespace App\Tests\Integrational;

use App\Domain\Entity\Actor;
use App\Domain\Entity\Genre;
use App\Domain\Entity\Movie as DomainMovie;
use App\Domain\Enum\MovieSource;
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
            ->findOneBy(['title_original' => 'Scarface']);
    }

    public function testFindById(): void
    {
        $movie = $this->getTestMovie();

        if ($movie === null) {
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

    private function getMovieId(): int
    {
        /** @var DoctrineMovie $movie */
        $movie = $this->entityManager->getReference(DomainMovie::class, $this->getReference(MovieFixture::SCARFACE)->getId());

        return $movie->getId();
    }
}

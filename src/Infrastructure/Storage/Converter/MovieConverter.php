<?php

namespace App\Infrastructure\Storage\Converter;

use App\Domain\Entity\Movie as DomainMovie;
use App\Infrastructure\Storage\Entity\Movie as DoctrineMovie;
use App\Domain\Entity\Genre as DomainGenre;
use App\Infrastructure\Storage\Entity\Genre as DoctrineGenre;
use App\Domain\Entity\Actor as DomainActor;
use App\Infrastructure\Storage\Entity\Actor as DoctrineActor;
use Doctrine\ORM\EntityManagerInterface;

readonly class MovieConverter
{
    public function __construct(
       private EntityManagerInterface $entityManager,
        private ActorConverter $actorConverter,
        private DirectorConverter $directorConverter,
        private GenreConverter $genreConverter,
        private CountryConverter $countryConverter,
    ) {
           }


    public function doctrineToDomain(DoctrineMovie $movie): DomainMovie
    {
        return new DomainMovie(
            $movie->getId(),
            $movie->getSource(),
            $movie->getTitle(),
            $movie->getDescription(),
            $movie->getTitleOriginal(),
            $movie->getYear(),
            $movie->getGenres()
                ->map(fn (DoctrineGenre $genre): DomainGenre => $this->genreConverter->doctrineToDomain($genre))
                ->toArray(),
            $this->directorConverter->doctrineToDomain($movie->getDirector()),
            $movie->getActors()
                ->map(fn (DoctrineActor $actor): DomainActor => $this->actorConverter->doctrineToDomain($actor))
                ->toArray(),
            $this->countryConverter->doctrineToDomain($movie->getCountry()),
            $movie->getRating(),
        );
    }

    public function domainToDoctrine(DomainMovie $movie): DoctrineMovie
    {
        $entity = $this->entityManager->getReference(DoctrineMovie::class, $movie->id);
        $entity->setFirstName($movie->firstName);
        $entity->setLastName($movie->lastName);
        $entity->setBirthday($movie->birthday);

        return $entity;
    }
}

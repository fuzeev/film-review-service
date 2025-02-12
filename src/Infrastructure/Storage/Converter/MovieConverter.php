<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\Converter;

use App\Domain\Entity\Actor as DomainActor;
use App\Domain\Entity\Genre as DomainGenre;
use App\Domain\Entity\Movie as DomainMovie;
use App\Infrastructure\Storage\Entity\Actor as DoctrineActor;
use App\Infrastructure\Storage\Entity\Genre as DoctrineGenre;
use App\Infrastructure\Storage\Entity\Movie as DoctrineMovie;
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

    public function doctrineToDomain(?DoctrineMovie $movie): ?DomainMovie
    {
        if ($movie === null) {
            return null;
        }

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

    public function domainToDoctrine(?DomainMovie $movie): ?DoctrineMovie
    {
        if ($movie === null) {
            return null;
        }

        /** @var DoctrineMovie $entity */
        $entity = $this->entityManager->getReference(DoctrineMovie::class, $movie->id);
        $entity->setSource($movie->source);
        $entity->setTitle($movie->title);
        $entity->setDescription($movie->description);
        $entity->setTitleOriginal($movie->titleOriginal);
        $entity->setYear($movie->year);
        array_map(
            fn (DomainGenre $genre) => $entity->addGenre($this->genreConverter->domainToDoctrine($genre)),
            $movie->genres
        );
        $entity->setDirector($this->directorConverter->domainToDoctrine($movie->director));
        array_map(
            fn (DomainActor $actor) => $entity->addActor($this->actorConverter->domainToDoctrine($actor)),
            $movie->actors
        );
        $entity->setCountry($this->countryConverter->domainToDoctrine($movie->country));
        $entity->setRating($movie->rating);

        return $entity;
    }
}

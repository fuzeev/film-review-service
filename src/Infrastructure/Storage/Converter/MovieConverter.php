<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\Converter;

use App\Domain\Entity\Actor as DomainActor;
use App\Domain\Entity\Genre as DomainGenre;
use App\Domain\Entity\Movie as DomainMovie;
use App\Infrastructure\Storage\Entity\Actor as DoctrineActor;
use App\Infrastructure\Storage\Entity\Genre as DoctrineGenre;
use App\Infrastructure\Storage\Entity\Movie as DoctrineMovie;
use App\Infrastructure\Storage\Exception\FailedToConvertException;
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

        $id = $movie->getId();
        $source = $movie->getSource();
        $title = $movie->getTitle();
        $description = $movie->getDescription();
        $titleOriginal = $movie->getTitleOriginal();
        $year = $movie->getYear();
        $director = $this->directorConverter->doctrineToDomain($movie->getDirector());
        $country = $this->countryConverter->doctrineToDomain($movie->getCountry());
        $rating = $movie->getRating();
        $genres = $movie->getGenres();
        $actors = $movie->getActors();

        if ($id === null
            || $source === null
            || $title === null
            || $description === null
            || $titleOriginal === null
            || $year === null
            || $director === null
            || $country === null
            || $rating === null
        ) {
            throw new FailedToConvertException(DoctrineMovie::class, DomainMovie::class);
        }

        return new DomainMovie(
            $id,
            $source,
            $title,
            $description,
            $titleOriginal,
            $year,
            $genres
                ->map(fn (DoctrineGenre $genre): ?DomainGenre => $this->genreConverter->doctrineToDomain($genre))
                ->toArray(),
            $director,
            $actors
                ->map(fn (DoctrineActor $actor): ?DomainActor => $this->actorConverter->doctrineToDomain($actor))
                ->toArray(),
            $country,
            $rating,
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
            function (DomainGenre $genre) use ($entity) {
                $genre = $this->genreConverter->domainToDoctrine($genre);
                if ($genre === null) {
                    return;
                }
                $entity->addGenre($genre);
            },
            $movie->genres
        );
        $entity->setDirector($this->directorConverter->domainToDoctrine($movie->director));
        array_map(
            function (DomainActor $actor) use ($entity) {
                $actor = $this->actorConverter->domainToDoctrine($actor);
                if ($actor === null) {
                    return;
                }
                $entity->addActor($actor);
            },
            $movie->actors
        );
        $entity->setCountry($this->countryConverter->domainToDoctrine($movie->country));
        $entity->setRating($movie->rating);

        return $entity;
    }
}

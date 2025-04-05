<?php

namespace App\Infrastructure\Storage\Converter;

use App\Domain\Entity\Review as DomainReview;
use App\Domain\Entity\User as DomainUser;
use App\Domain\Enum\UserRole;
use App\Infrastructure\Storage\Entity\User as DoctrineUser;
use App\Infrastructure\Storage\Entity\Review as DoctrineReview;
use App\Infrastructure\Storage\Exception\FailedToConvertException;
use Doctrine\ORM\EntityManagerInterface;

class ReviewConverter
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MovieConverter $movieConverter,
        private UserConverter $userConverter,
    ) {
    }

    public function doctrineToDomain(?DoctrineReview $review): ?DomainReview
    {
        if ($review === null) {
            return null;
        }

        $id = $review->getId();
        $author = $review->getAuthor();
        $movie = $review->getMovie();
        $title = $review->getTitle();
        $text = $review->getText();
        $rating = $review->getRating();

        if ($id === null || $author === null || $movie === null || $title === null || $text === null
            || $rating === null || $rating < 1 || $rating > 10) {
            throw new FailedToConvertException(DomainUser::class, DoctrineUser::class);
        }

        $author = $this->userConverter->doctrineToDomain($author);
        $movie = $this->movieConverter->doctrineToDomain($movie);

        return new DomainReview($id, $author, $movie, $title, $text, $rating);
    }

    public function domainToDoctrine(?DomainReview $review): ?DoctrineReview
    {
        if ($review === null) {
            return null;
        }

        /** @var DoctrineReview $entity */
        $entity = $this->entityManager->getReference(DoctrineReview::class, $review->id);

        $author = $this->userConverter->domainToDoctrine($review->author);
        $entity->setAuthor($author);

        $movie = $this->movieConverter->domainToDoctrine($review->movie);
        $entity->setMovie($movie);

        $entity->setTitle($review->title);
        $entity->setText($review->text);
        $entity->setRating($review->rating);

        return $entity;
    }
}

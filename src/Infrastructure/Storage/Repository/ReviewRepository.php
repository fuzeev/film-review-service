<?php

namespace App\Infrastructure\Storage\Repository;

use App\Domain\Dto\CreateReviewDto;
use App\Domain\Repository\IReviewRepository;
use App\Infrastructure\Storage\Converter\MovieConverter;
use App\Infrastructure\Storage\Converter\ReviewConverter;
use App\Infrastructure\Storage\Converter\UserConverter;
use App\Infrastructure\Storage\Entity\Review as DoctrineReview;
use App\Domain\Entity\Review as DomainReview;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DoctrineReview>
 */
class ReviewRepository extends ServiceEntityRepository implements IReviewRepository
{
    use EntityRepositoryTrait;

    public function __construct(
        ManagerRegistry $registry,
        readonly private MovieConverter $movieConverter,
        readonly private UserConverter $userConverter,
        readonly private ReviewConverter $reviewConverter,
    ) {
        parent::__construct($registry, DoctrineReview::class);
    }

    public function checkReviewExists(int $userId, int $movieId): bool
    {
        $result = $this->createQueryBuilder('r')
            ->select('1')
            ->andWhere('IDENTITY(r.author) = :userId')
            ->setParameter('userId', $userId)
            ->andWhere('IDENTITY(r.movie) = :movieId')
            ->setParameter('movieId', $movieId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return $result !== null;
    }

    public function createReview(CreateReviewDto $dto): DomainReview
    {
        $newReview = new DoctrineReview();
        $newReview->setTitle($dto->title);
        $newReview->setText($dto->text);
        $newReview->setRating($dto->rating);

        $movie = $this->movieConverter->domainToDoctrine($dto->movie);
        $newReview->setMovie($movie);

        $author = $this->userConverter->domainToDoctrine($dto->author);
        $newReview->setAuthor($author);

        $em = $this->getEntityManager();
        $em->persist($newReview);
        $em->flush();

        return $this->reviewConverter->doctrineToDomain($newReview);
    }
}

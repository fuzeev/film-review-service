<?php

namespace App\Domain\Repository;

use App\Domain\Dto\CreateReviewDto;
use App\Domain\Entity\Review;

interface IReviewRepository extends IEntityRepository
{
    public function createReview(CreateReviewDto $dto): Review;
    public function checkReviewExists(int $userId, int $movieId): bool;
}

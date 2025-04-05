<?php

namespace App\Application\Usecase;

use App\Application\Dto\CreateReviewRequest;
use App\Application\Dto\CreateReviewResult;
use App\Domain\Dto\CreateReviewDto;
use App\Domain\Repository\IMovieRepository;
use App\Domain\Repository\IReviewRepository;
use App\Domain\Repository\IUserRepository;

class CreateReviewUsecase
{
    public function __construct(
        protected IReviewRepository $reviewRepository,
        protected IUserRepository $userRepository,
        protected IMovieRepository $movieRepository,
    ) {
    }

    public function execute(CreateReviewRequest $request): CreateReviewResult
    {
        $errors = [];
        if ($this->reviewRepository->checkReviewExists($request->authorId, $request->movieId)) {
            $errors['authorId'] = "Отзыв на этот фильм уже существует";
            return CreateReviewResult::error($errors);
        }

        $user = $this->userRepository->getById($request->authorId);
        if (!$user) {
            $errors['authorId'] = "Некорректный id пользователя";
            return CreateReviewResult::error($errors);
        }

        $movie = $this->movieRepository->getById($request->movieId);
        if (!$movie) {
            $errors['movieid'] = "Некорректный id фильма";
            return CreateReviewResult::error($errors);
        }

        $dto = new CreateReviewDto(
            $user,
            $movie,
            $request->title,
            $request->text,
            $request->rating
        );

        $review = $this->reviewRepository->createReview($dto);

        return CreateReviewResult::success($review->id);
    }
}

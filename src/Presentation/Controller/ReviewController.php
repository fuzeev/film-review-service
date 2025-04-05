<?php

namespace App\Presentation\Controller;

use App\Application\Dto\CreateReviewRequest;
use App\Application\Usecase\CreateReviewUsecase;
use JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReviewController extends AbstractController
{
    public function __construct(
        protected CreateReviewUsecase $createReviewUsecase,
        protected ValidatorInterface $validator,
    ) {
    }

    #[Route('/create-review', name: 'create_review', methods: ['POST'])]
    public function getMovieListHandler(Request $request): Response
    {
        try {
            $requestDecoded = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return $this->json('Invalid Json', 400);
        }

        $dto = new CreateReviewRequest(
            title: $requestDecoded['title'] ?? null,
            text: $requestDecoded['text'] ?? null,
            rating: $requestDecoded['rating'] ?? null,
            authorId: $this->getUser()->getId(),
            movieId: $requestDecoded['movieId'] ?? null,
        );

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return $this->validationErrorResponse($errors);
        }

        $result = $this->createReviewUsecase->execute($dto);

        if (! $result->success) {
            return $this->json($result->toArray(), Response::HTTP_BAD_REQUEST);
        }

        return $this->json($result->toArray(), Response::HTTP_CREATED);
    }
}

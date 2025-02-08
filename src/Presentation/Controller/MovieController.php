<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Application\Dto\GetMovieByIdRequest;
use App\Application\Usecase\GetMovieByIdUsecase;
use App\Domain\Entity\Actor;
use App\Domain\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MovieController extends AbstractController
{
    public function __construct(
        protected GetMovieByIdUsecase $getMovieByIdUsecase,
        protected ValidatorInterface $validator,
    ) {
    }

    #[Route('/movie/{id}', name: 'get_movie_by_id', methods: ['GET'])]
    public function getByIdHandler(int $id): Response
    {
        $requestDto = new GetMovieByIdRequest($id);

        $errors = $this->validator->validate($requestDto);
        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $result = $this->getMovieByIdUsecase->execute($requestDto);

        if (! $result->movie) {
            throw new NotFoundHttpException();
        }

        return $this->json($this->movieAsArray($result->movie));
    }

    protected function movieAsArray(Movie $movie): array
    {
        $actors = array_map(fn (Actor $actor) => $actor->getShortName(), $movie->actors);

        return [
            'id' => $movie->id,
            'title' => $movie->title,
            'description' => $movie->description,
            'year' => $movie->year,
            'director' => $movie->director->getShortName(),
            'source' => $movie->source->value,
            'actors' => $actors,
        ];
    }
}

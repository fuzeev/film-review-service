<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Application\Dto\GetMovieByIdRequest;
use App\Application\Usecase\GetMovieByIdUsecase;
use App\Domain\Entity\Actor;
use App\Domain\Entity\Genre;
use App\Domain\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
            return $this->json(null, 404);
        }

        return $this->json($this->createArrayMovieResponse($result->movie));
    }

    protected function createArrayMovieResponse(Movie $movie): array
    {
        $actors = array_map(fn (Actor $actor) => [
            'id' => $actor->id,
            'name' => $actor->getShortName(),
        ], $movie->actors);

        $genres = array_map(fn (Genre $genre) => [
            'id' => $genre->id,
            'name' => $genre->name,
        ], $movie->genres);

        return [
            'id' => $movie->id,
            'title' => $movie->title,
            'description' => $movie->description,
            'titleOriginal' => $movie->titleOriginal,
            'genres' => $genres,
            'year' => $movie->year,
            'director' => $movie->director->getShortName(),
            'source' => $movie->source->value,
            'coutry' => $movie->country,
            'actors' => $actors,
        ];
    }
}

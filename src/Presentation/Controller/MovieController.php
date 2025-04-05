<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Application\Dto\AddMovieRequest;
use App\Application\Dto\GetMovieByIdRequest;
use App\Application\Dto\GetMovieListRequest;
use App\Application\Usecase\AddMovieUsecase;
use App\Application\Usecase\GetMovieByIdUsecase;
use App\Application\Usecase\GetMovieListUsecase;
use App\Domain\Entity\Actor;
use App\Domain\Entity\Genre;
use App\Domain\Entity\Movie;
use JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MovieController extends AbstractController
{
    public function __construct(
        protected GetMovieByIdUsecase $getMovieByIdUsecase,
        protected AddMovieUsecase $addMovieUsecase,
        protected GetMovieListUsecase $getMovieListUsecase,
        protected ValidatorInterface $validator,
    ) {
    }

    #[Route('/movie/{id}', name: 'get_movie_by_id', methods: ['GET'])]
    public function getByIdHandler(int $id): Response
    {
        $requestDto = new GetMovieByIdRequest($id);

        $errors = $this->validator->validate($requestDto);
        if (count($errors) > 0) {
            return $this->validationErrorResponse($errors);
        }

        $result = $this->getMovieByIdUsecase->execute($requestDto);

        if (! $result->movie) {
            return $this->json(null, Response::HTTP_NOT_FOUND);
        }

        return $this->json($this->createMovieResponse($result->movie));
    }

    #[Route('/movie', name: 'add_movie', methods: ['POST'])]
    public function addMovieHandler(Request $request): Response
    {
        try {
            $requestDecoded = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return $this->json('Invalid Json', 400);
        }

        $dto = new AddMovieRequest(
            title: $requestDecoded['title'] ?? null,
            description: $requestDecoded['description'] ?? null,
            titleOriginal: $requestDecoded['titleOriginal'] ?? null,
            year: $requestDecoded['year'] ?? null,
            genreIds: $requestDecoded['genreIds'] ?? null,
            directorId: $requestDecoded['directorId'] ?? null,
            actorIds: $requestDecoded['actorIds'] ?? null,
            countryId: $requestDecoded['countryId'] ?? null,
        );

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return $this->validationErrorResponse($errors);
        }

        $result = $this->addMovieUsecase->execute($dto);

        if (! $result->success) {
            return $this->json($result->toArray(), Response::HTTP_BAD_REQUEST);
        }

        return $this->json($result->toArray(), Response::HTTP_CREATED);
    }

    #[Route('/movies', name: 'get_movie_list', methods: ['POST'])]
    public function getMovieListHandler(Request $request): Response
    {
        try {
            $requestDecoded = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return $this->json('Invalid Json', 400);
        }

        $dto = new GetMovieListRequest(
            actorId: $requestDecoded['actorId'] ?? null,
            directorId: $requestDecoded['directorId'] ?? null,
            title: $requestDecoded['title'] ?? null,
            titleOriginal: $requestDecoded['titleOriginal'] ?? null,
            yearStart: $requestDecoded['yearStart'] ?? null,
            yearEnd: $requestDecoded['yearEnd'] ?? null,
            countryId: $requestDecoded['countryId'] ?? null,
            genreId: $requestDecoded['genreIds'] ?? null,
            ratingMin: $requestDecoded['ratingMin'] ?? null,
            sortBy: $requestDecoded['sortBy'] ?? null,
            sortType: $requestDecoded['sortType'] ?? null,
            limit: $requestDecoded['limit'] ?? null,
            offset: $requestDecoded['offset'] ?? null,
        );

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return $this->validationErrorResponse($errors);
        }

        $result = $this->getMovieListUsecase->execute($dto);

        $movies = $result->movies
            ? array_map(fn (Movie $movie) => $this->createMovieResponse($movie), $result->movies)
            : null;

        return $this->json([
            'errors' => $result->errors,
            'movies' => $movies,
            'totalCount' => $result->totalCount,
            'limit' => $result->limit,
            'offset' => $result->offset,
            'sortBy' => $result->sortBy,
            'sortType' => $result->sortType,
        ], Response::HTTP_OK);
    }

    protected function createMovieResponse(Movie $movie): array
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
            'director' => [
                'id' => $movie->director->id,
                'name' => $movie->director->getShortName(),
            ],
            'source' => $movie->source->value,
            'coutry' => $movie->country,
            'actors' => $actors,
        ];
    }
}

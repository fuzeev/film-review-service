<?php

declare(strict_types=1);

namespace App\Application\Usecase;

use App\Application\Dto\AddMovieRequest;
use App\Application\Dto\AddMovieResult;
use App\Domain\Dto\AddMovieDto;
use App\Domain\Enum\MovieSource;
use App\Domain\Repository\IActorRepository;
use App\Domain\Repository\ICountryRepository;
use App\Domain\Repository\IDirectorRepository;
use App\Domain\Repository\IGenreRepository;
use App\Domain\Repository\IMovieRepository;

class AddMovieUsecase
{
    public function __construct(
        protected IMovieRepository $movieRepository,
        protected IGenreRepository $genreRepository,
        protected ICountryRepository $countryRepository,
        protected IActorRepository $actorRepository,
        protected IDirectorRepository $directorRepository,
    ) {
    }

    protected function errorResponse(string $message = ''): AddMovieResult
    {
        if ($message === '') {
            $message = 'Возникла неизвестная ошибка';
        }

        return new AddMovieResult(
            false,
            null,
            $message,
        );
    }

    protected function successResponse(int $movieId): AddMovieResult
    {
        return new AddMovieResult(
            true,
            $movieId,
            null,
        );
    }

    protected function findNonExistentGenreIds(array $genreIds): array
    {
        return $this->genreRepository->findNonExistentIds($genreIds);
    }

    protected function findNonExistentActorIds(array $actorTds): array
    {
        return $this->actorRepository->findNonExistentIds($actorTds);
    }

    protected function checkDirectorId(int $directorId): bool
    {
        return $this->directorRepository->checkIdExists($directorId);
    }

    protected function checkCountryId(int $countryId): bool
    {
        return $this->countryRepository->checkIdExists($countryId);
    }

    public function execute(AddMovieRequest $request): AddMovieResult
    {
        $dto = new AddMovieDto(
            source:  MovieSource::MANUAL,
            title: $request->title,
            description: $request->description,
            titleOriginal: $request->titleOriginal,
            year: $request->year,
            genreIds: $request->genreIds,
            directorId: $request->directorId,
            actorIds: $request->actorIds,
            countryId: $request->countryId,
        );

        $errors = [];
        if ($nonExistingGenres = $this->findNonExistentGenreIds($dto->genreIds)) {
            $errors[] = "Следующие жанры не существуют: " . implode(', ', $nonExistingGenres);
        }
        if ($nonExistingActors = $this->findNonExistentActorIds($dto->actorIds)) {
            $errors[] = "Следующие актеры не существуют: " . implode(', ', $nonExistingActors);
        }
        if (!$this->checkDirectorId($dto->directorId)) {
            $errors[] = "Режиссер $dto->directorId не существует";
        }
        if (!$this->checkCountryId($dto->countryId)) {
            $errors[] = "Страна $dto->countryId не существует";
        }

        if ($errors !== []) {
            return $this->errorResponse(implode(', ', $errors));
        }

        $movie = $this->movieRepository->add($dto);

        return $this->successResponse($movie->id);
    }
}

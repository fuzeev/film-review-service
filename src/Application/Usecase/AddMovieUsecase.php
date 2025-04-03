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

    public function execute(AddMovieRequest $request): AddMovieResult
    {
        $dto = new AddMovieDto(
            source: MovieSource::MANUAL,
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
        if ($nonExistingGenres = $this->genreRepository->findNonExistentIds($dto->genreIds)) {
            $errors['genreIds'] = 'Следующие жанры не существуют: ' . implode(', ', $nonExistingGenres);
        }
        if ($nonExistingActors = $this->actorRepository->findNonExistentIds($dto->actorIds)) {
            $errors['actorIds'] = 'Следующие актеры не существуют: ' . implode(
                ', ',
                $nonExistingActors
            );
        }
        if (! $this->directorRepository->checkIdExists($dto->directorId)) {
            $errors['directorId'] = "Режиссер {$dto->directorId} не существует";
        }
        if (! $this->countryRepository->checkIdExists($dto->countryId)) {
            $errors['countryId'] = "Страна {$dto->countryId} не существует";
        }

        if ($errors !== []) {
            return AddMovieResult::error($errors);
        }

        $movie = $this->movieRepository->add($dto);

        return AddMovieResult::success($movie->id);
    }
}

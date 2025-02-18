<?php

declare(strict_types=1);

namespace App\Application\Usecase;

use App\Application\Dto\AddMovieRequest;
use App\Application\Dto\AddMovieResponse;
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

    protected function errorResponse(string $message): AddMovieResponse
    {
        return new AddMovieResponse(
            false,
            null,
            $message,
        );
    }

    protected function successResponse(int $movieId): AddMovieResponse
    {
        return new AddMovieResponse(
            true,
            $movieId,
            null,
        );
    }

    public function execute(AddMovieRequest $request): AddMovieResponse
    {





    }
}

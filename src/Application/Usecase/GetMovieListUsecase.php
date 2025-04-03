<?php

declare(strict_types=1);

namespace App\Application\Usecase;

use App\Application\Dto\GetMovieListRequest;
use App\Application\Dto\GetMovieListResult;
use App\Domain\Dto\GetMovieListQuery;
use App\Domain\Enum\MovieListSortField;
use App\Domain\Enum\MovieListSortType;
use App\Domain\Repository\IActorRepository;
use App\Domain\Repository\ICountryRepository;
use App\Domain\Repository\IDirectorRepository;
use App\Domain\Repository\IGenreRepository;
use App\Domain\Repository\IMovieRepository;

class GetMovieListUsecase
{
    public function __construct(
        protected IMovieRepository $movieRepository,
        protected IGenreRepository $genreRepository,
        protected ICountryRepository $countryRepository,
        protected IActorRepository $actorRepository,
        protected IDirectorRepository $directorRepository,
    ) {
    }

    public function execute(GetMovieListRequest $dto): GetMovieListResult
    {
        $errors = [];
        if ($dto->actorId && ! $this->actorRepository->checkIdExists($dto->actorId)) {
            $errors[] = "Актер с id $dto->actorId не найден";
        }

        if ($dto->directorId && ! $this->directorRepository->checkIdExists($dto->directorId)) {
            $errors[] = "Режиссер с id $dto->actorId не найден";
        }

        if ($dto->countryId && ! $this->countryRepository->checkIdExists($dto->countryId)) {
            $errors[] = "Страна с id $dto->actorId не найдена";
        }

        if ($dto->genreId && ! $this->genreRepository->checkIdExists($dto->genreId)) {
            $errors[] = "Жанр с id $dto->actorId не найден";
        }

        if (! empty($errors)) {
            return GetMovieListResult::error($errors);
        }

        $queryObject = new GetMovieListQuery(
            actorId: $dto->actorId,
            directorId: $dto->directorId,
            title: $dto->title,
            titleOriginal: $dto->titleOriginal,
            yearStart: $dto->yearStart,
            yearEnd: $dto->yearEnd,
            countryId: $dto->countryId,
            genreId: $dto->genreId,
            ratingMin: $dto->ratingMin,
            sortBy: MovieListSortField::tryFrom($dto->sortBy),
            sortType: MovieListSortType::tryFrom($dto->sortType),
            limit: $dto->limit,
            offset: $dto->offset,
        );

        $repositoryResult = $this->movieRepository->getList($queryObject);

        return GetMovieListResult::success(
            movies: $repositoryResult->movies,
            limit: $repositoryResult->limit,
            offset: $repositoryResult->offset,
            totalCount: $repositoryResult->totalCount,
            sortBy: $repositoryResult->sortBy,
            sortType: $repositoryResult->sortType,
        );
    }
}

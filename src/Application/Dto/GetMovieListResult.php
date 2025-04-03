<?php

namespace App\Application\Dto;

use App\Domain\Entity\Movie;
use App\Domain\Enum\MovieListSortField;
use App\Domain\Enum\MovieListSortType;

readonly class GetMovieListResult
{
    /**
     * @param Movie[] $movies
     */
    private function __construct(
        public ?array $errors = null,
        public ?array $movies = null,
        public ?int $limit = null,
        public ?int $offset = null,
        public ?int $totalCount = null,
        public ?MovieListSortField $sortBy = null,
        public ?MovieListSortType $sortType = null,
    ) {
    }

    public static function error(array $errors): self
    {
        $errors = array_map(fn ($field, $error) => [
            'field' => is_int($field) ? null : $field,
            'error' => $error,
        ], array_keys($errors), $errors);

        return new self(
            $errors,
        );
    }

    public static function success(
        array $movies,
        int $limit,
        int $offset,
        int $totalCount,
        MovieListSortField $sortBy,
        MovieListSortType $sortType,
    ): self
    {
        return new self(
            movies: $movies,
            limit: $limit,
            offset: $offset,
            totalCount: $totalCount,
            sortBy: $sortBy,
            sortType: $sortType,
        );
    }
}

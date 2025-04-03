<?php

declare(strict_types=1);

namespace App\Domain\Dto;

use App\Domain\Entity\Movie;
use App\Domain\Enum\MovieListSortField;
use App\Domain\Enum\MovieListSortType;

readonly class GetMovieListResult
{
    /**
     * @param Movie[] $movies
     */
    public function __construct(
        public array $movies,
        public int $limit,
        public int $offset,
        public int $totalCount,
        public MovieListSortField $sortBy,
        public MovieListSortType $sortType,
    ) {
    }
}

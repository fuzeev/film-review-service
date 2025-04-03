<?php

declare(strict_types=1);

namespace App\Domain\Dto;

use App\Domain\Enum\MovieListSortField;
use App\Domain\Enum\MovieListSortType;

readonly class GetMovieListQuery
{
    public function __construct(
        public ?int $actorId,
        public ?int $directorId,
        public ?string $title,
        public ?string $titleOriginal,
        public ?int $yearStart,
        public ?int $yearEnd,
        public ?int $countryId,
        public ?int $genreId,
        public ?float $ratingMin,
        public ?MovieListSortField $sortBy,
        public ?MovieListSortType $sortType,
        public ?int $limit,
        public ?int $offset,
    ) {
    }
}

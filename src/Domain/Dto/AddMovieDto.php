<?php

declare(strict_types=1);

namespace App\Domain\Dto;

use App\Domain\Enum\MovieSource;

readonly class AddMovieDto
{
    public function __construct(
        public MovieSource $source,
        public string $title,
        public string $description,
        public string $titleOriginal,
        public int $year,
        public array $genreIds,
        public int $directorId,
        public array $actorIds,
        public int $countryId,
    ) {
    }
}

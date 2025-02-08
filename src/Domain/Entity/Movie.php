<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Enum\MovieSource;

class Movie
{
    public function __construct(
        public int $id,
        public MovieSource $source,
        public string $title,
        public string $description,
        public string $nameLocalized,
        public string $nameOriginal,
        public int $year,
        public array $genres, //Genres[]
        public Director $director,
        public array $actors, //Actors[]
        public float $rating,
    ) {
    }
}

<?php

namespace App\Application\Dto;

use App\Domain\Enum\MovieListSortField;
use App\Domain\Enum\MovieListSortType;
use Symfony\Component\Validator\Constraints as Assert;

class GetMovieListRequest
{
    public function __construct(
        #[Assert\Type('integer')]
        #[Assert\Positive]
        public $actorId,

        #[Assert\Type('integer')]
        #[Assert\Positive]
        public $directorId,

        #[Assert\Type('string')]
        #[Assert\Length(max: 255)]
        public $title,

        #[Assert\Type('string')]
        #[Assert\Length(max: 255)]
        public $titleOriginal,

        #[Assert\Type('integer')]
        #[Assert\Range(min: 1900, max: 2100)]
        public $yearStart,

        #[Assert\Type('integer')]
        #[Assert\Range(min: 1900, max: 2100)]
        public $yearEnd,

        #[Assert\Type('integer')]
        #[Assert\Positive]
        public $countryId,

        #[Assert\Type('integer')]
        #[Assert\Positive]
        public $genreId,

        #[Assert\Type('numeric')]
        #[Assert\Range(min: 0, max: 10)]
        public $ratingMin,

        #[Assert\Type('string')]
        #[Assert\Choice(callback: [MovieListSortField::class, 'values'])]
        public $sortBy,

        #[Assert\Type('string')]
        #[Assert\Choice(callback: [MovieListSortType::class, 'values'])]
        public $sortType,

        #[Assert\Type('integer')]
        #[Assert\Positive]
        #[Assert\Range(max: 100)]
        public $limit,

        #[Assert\Type('integer')]
        #[Assert\GreaterThanOrEqual(0)]
        public $offset,
    ) {
        if ($this->offset === null) {
            $this->offset = 0;
        }
        if ($this->limit === null) {
            $this->limit = 10;
        }
        if ($this->sortBy === null) {
            $this->sortBy = MovieListSortField::ID->value;
        }
        if ($this->sortType === null) {
            $this->sortType = MovieListSortType::DESC->value;
        }
    }
}

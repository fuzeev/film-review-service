<?php

declare(strict_types=1);

namespace App\Application\Dto;

use App\Domain\Enum\MovieSource;
use Symfony\Component\Validator\Constraints as Assert;

class AddMovieRequest
{
    public function __construct(
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 255)]
        public $title,
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[Assert\Length(min: 30, max: 1000)]
        public $description,
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 255)]
        public $titleOriginal,
        #[Assert\Type('integer')]
        #[Assert\NotBlank]
        #[Assert\Range(min: 1900, max: 2100)]
        public $year,
        #[Assert\Type('array')]
        #[Assert\NotBlank]
        #[Assert\All([
            new Assert\Type('integer'),
            new Assert\Positive,
        ])]
        public $genreIds,
        #[Assert\Type('integer')]
        #[Assert\Positive]
        #[Assert\NotBlank]
        public $directorId,
        #[Assert\Type('array')]
        #[Assert\All([
            new Assert\Type('integer'),
            new Assert\Positive,
        ])]
        #[Assert\NotBlank]
        public $actorIds,
        #[Assert\Type('integer')]
        #[Assert\Positive]
        #[Assert\NotBlank]
        public $countryId,
    ) {
    }
}

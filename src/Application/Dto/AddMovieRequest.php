<?php

declare(strict_types=1);

namespace App\Application\Dto;

use App\Domain\Enum\MovieSource;
use Symfony\Component\Validator\Constraints as Assert;

readonly class AddMovieRequest
{
    public function __construct(
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 255)]
        public string $title,
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[Assert\Length(min: 30, max: 1000)]
        public string $description,
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 255)]
        public string $titleOriginal,
        #[Assert\Type('integer')]
        #[Assert\Range(min: 1900, max: 2100)]
        public int $year,
        #[Assert\Type('array')]
        #[Assert\All([
            new Assert\Type('integer'),
            new Assert\Positive,
        ])]
        public array $genreIds,
        #[Assert\Type('integer')]
        #[Assert\Positive]
        public int $directorId,
        #[Assert\Type('array')]
        #[Assert\All([
            new Assert\Type('integer'),
            new Assert\Positive,
        ])]
        public array $actorIds,
        #[Assert\Type('integer')]
        #[Assert\Positive]
        public int $countryId,
    ) {
    }
}

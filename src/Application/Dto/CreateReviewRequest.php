<?php

namespace App\Application\Dto;

use App\Domain\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class CreateReviewRequest
{
    public function __construct(
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[Assert\Length(min: 10, max: 255)]
        public $title,
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[Assert\Length(min: 50, max: 1024)]
        public $text,
        #[Assert\Type('integer')]
        #[Assert\LessThanOrEqual(10)]
        #[Assert\GreaterThanOrEqual(1)]
        public $rating,
        #[Assert\NotBlank]
        #[Assert\Type('int')]
        #[Assert\GreaterThan(0)]
        public $authorId,
        #[Assert\NotBlank]
        #[Assert\Type('int')]
        #[Assert\GreaterThan(0)]
        public $movieId,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class GetMovieByIdRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('int')]
        #[Assert\GreaterThan(0)]
        public $movieId,
    ) {
    }
}

<?php

namespace App\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CreateAccountRequest
{
    public function __construct(
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 255)]
        public $firstName,
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 255)]
        public $lastName,
        #[Assert\Type('string')]
        #[Assert\Length(min: 1, max: 255)]
        public $middleName,
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[Assert\DateTime(
            format: 'Y-m-d',
            message: 'Invalid date. Use YYYY-MM-DD format.',
        )]
        #[Assert\LessThanOrEqual(
            value: 'today',
            message: 'Invalid birthday.',
        )]
        public $birthday,
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[Assert\Email]
        public $email,
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 255)]
        public $username,
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[Assert\Length(min: 8, max: 255)]
        public $password,
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        public $confirmPassword,
    ) {
    }
}

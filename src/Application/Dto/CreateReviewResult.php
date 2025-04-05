<?php

declare(strict_types=1);

namespace App\Application\Dto;

readonly class CreateReviewResult
{
    private function __construct(
        public bool $success,
        public ?int $reviewId,
        public ?array $errors,
    ) {
    }

    public static function success(int $reviewId): self
    {
        return new self(true, $reviewId, null);
    }

    public static function error(array $errors): self
    {
        $errors = array_map(fn ($field, $error) => [
            'field' => is_int($field) ? null : $field,
            'error' => $error,
        ], array_keys($errors), $errors);

        return new self(false, null, $errors);
    }
}

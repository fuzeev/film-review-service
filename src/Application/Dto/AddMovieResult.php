<?php

declare(strict_types=1);

namespace App\Application\Dto;

readonly class AddMovieResult
{
    private function __construct(
        public bool $success,
        public ?int $movieId,
        public ?array $errors,
    ) {
    }

    public static function success(int $movieId): self
    {
        return new self(true, $movieId, null);
    }

    public static function error(array $errors): self
    {
        $errors = array_map(fn ($field, $error) => [
            'field' => is_int($field) ? null : $field,
            'error' => $error,
        ], array_keys($errors), $errors);

        return new self(false, null, $errors);
    }

    public function toArray(): array
    {
        return [
            'success'   => $this->success,
            'movieId'  => $this->movieId,
            'errors'    => $this->errors,
        ];
    }
}

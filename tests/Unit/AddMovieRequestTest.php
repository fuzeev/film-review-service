<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Application\Dto\AddMovieRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddMovieRequestTest extends TestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    public function testValidAddMovieRequest(): void
    {
        $dto = new AddMovieRequest(
            'Movie Title',
            str_repeat('Description text ', 3), // достаточно длинное описание (не менее 30 символов)
            'Original Movie Title',
            2020,
            [1, 2, 3],
            1,
            [4, 5],
            1
        );

        $errors = $this->validator->validate($dto);
        $this->assertCount(0, $errors, 'Валидный DTO не должен содержать ошибок.');
    }

    public function testInvalidTitle(): void
    {
        $dto = new AddMovieRequest(
            '', // пустой title
            str_repeat('Description text ', 3),
            'Original Movie Title',
            2020,
            [1, 2, 3],
            1,
            [4, 5],
            1
        );

        $errors = $this->validator->validate($dto);

        $this->assertGreaterThan(0, $errors->count(), 'Пустой title должен приводить к ошибке валидации.');
    }

    public function testInvalidDescriptionTooShort(): void
    {
        $dto = new AddMovieRequest(
            'Movie Title',
            'Too short', // описание менее 30 символов
            'Original Movie Title',
            2020,
            [1, 2, 3],
            1,
            [4, 5],
            1
        );

        $errors = $this->validator->validate($dto);
        $this->assertGreaterThan(0, $errors->count(), 'Короткое описание должно приводить к ошибке валидации.');
    }

    public function testInvalidYear(): void
    {
        $dto = new AddMovieRequest(
            'Movie Title',
            str_repeat('Description text ', 3),
            'Original Movie Title',
            1800, // год вне допустимого диапазона (от 1900 до 2100)
            [1, 2, 3],
            1,
            [4, 5],
            1
        );

        $errors = $this->validator->validate($dto);
        $this->assertGreaterThan(0, $errors->count(), 'Неверный год должен приводить к ошибке валидации.');
    }

    public function testInvalidGenreIds(): void
    {
        $dto = new AddMovieRequest(
            'Movie Title',
            str_repeat('Description text ', 3),
            'Original Movie Title',
            2020,
            [1, -2, 3], // -2 не является положительным числом
            1,
            [4, 5],
            1
        );

        $errors = $this->validator->validate($dto);
        $this->assertGreaterThan(0, $errors->count(), 'Неверный массив genreIds должен приводить к ошибке валидации.');
    }

    public function testInvalidDirectorId(): void
    {
        $dto = new AddMovieRequest(
            'Movie Title',
            str_repeat('Description text ', 3),
            'Original Movie Title',
            2020,
            [1, 2, 3],
            0, // 0 не является положительным числом
            [4, 5],
            1
        );

        $errors = $this->validator->validate($dto);
        $this->assertGreaterThan(0, $errors->count(), 'Неверный directorId должен приводить к ошибке валидации.');
    }

    public function testInvalidActorIds(): void
    {
        $dto = new AddMovieRequest(
            'Movie Title',
            str_repeat('Description text ', 3),
            'Original Movie Title',
            2020,
            [1, 2, 3],
            1,
            [4, 0], // 0 не является положительным числом
            1
        );

        $errors = $this->validator->validate($dto);
        $this->assertGreaterThan(0, $errors->count(), 'Неверный массив actorIds должен приводить к ошибке валидации.');
    }

    public function testInvalidCountryId(): void
    {
        $dto = new AddMovieRequest(
            'Movie Title',
            str_repeat('Description text ', 3),
            'Original Movie Title',
            2020,
            [1, 2, 3],
            1,
            [4, 5],
            -1 // отрицательное значение недопустимо
        );

        $errors = $this->validator->validate($dto);
        $this->assertGreaterThan(0, $errors->count(), 'Неверный countryId должен приводить к ошибке валидации.');
    }
}

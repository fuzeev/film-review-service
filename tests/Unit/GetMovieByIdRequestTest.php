<?php

namespace App\Tests\Unit;

use App\Application\Dto\GetMovieByIdRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GetMovieByIdRequestTest extends TestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        // Создание валидатора с поддержкой аннотаций
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    public function testValidMovieId(): void
    {
        $dto = new GetMovieByIdRequest(5); // Валидный идентификатор

        $errors = $this->validator->validate($dto);
        $this->assertCount(0, $errors, 'Валидный movieId не должен приводить к ошибкам.');
    }

    public function testMovieIdIsBlank(): void
    {
        $dto = new GetMovieByIdRequest(null); // Пустое значение

        $errors = $this->validator->validate($dto);
        $this->assertGreaterThan(0, $errors->count(), 'movieId не может быть пустым.');
    }

    public function testMovieIdIsZero(): void
    {
        $dto = new GetMovieByIdRequest(0); // Недопустимый идентификатор (должен быть > 0)

        $errors = $this->validator->validate($dto);
        $this->assertGreaterThan(0, $errors->count(), 'movieId не может быть 0.');
    }

    public function testMovieIdIsNegative(): void
    {
        $dto = new GetMovieByIdRequest(-1); // Недопустимое отрицательное значение

        $errors = $this->validator->validate($dto);
        $this->assertGreaterThan(0, $errors->count(), 'movieId не может быть отрицательным.');
    }

    public function testMovieIdIsString(): void
    {
        $dto = new GetMovieByIdRequest('abc'); // Строка вместо числа

        $errors = $this->validator->validate($dto);
        $this->assertGreaterThan(0, $errors->count(), 'movieId должен быть числом.');
    }
}

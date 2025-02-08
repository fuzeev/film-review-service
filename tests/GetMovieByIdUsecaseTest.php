<?php

declare(strict_types=1);

namespace App\Tests;

use App\Application\Dto\GetMovieByIdRequest;
use App\Application\Dto\GetMovieByIdResponse;
use App\Application\Usecase\GetMovieByIdUsecase;
use App\Domain\Entity\Actor;
use App\Domain\Entity\Director;
use App\Domain\Entity\Genre;
use App\Domain\Entity\Movie;
use App\Domain\Enum\MovieSource;
use App\Domain\Repository\IMovieRepository;
use PHPUnit\Framework\TestCase;

class GetMovieByIdUsecaseTest extends TestCase
{
    protected function createTestMovieWithId(int $id)
    {
        return new Movie(
            $id,
            MovieSource::IMDB,
            'testTitle',
            'testDescription',
            'testName',
            'testNameOriginal',
            2024,
            [new Genre(1, 'Комедия'), new Genre(2, 'Драма')],
            new Director(
                'Никита',
                'Михалков',
                'Сергеевич',
                new \DateTimeImmutable('1958-01-01')
            ),
            [
                new Actor('Иван', 'Иванов', 'Иванович', new \DateTimeImmutable('2000-01-01')),
                new Actor('Анна', 'Иванова', 'Ивановна', new \DateTimeImmutable('2000-01-01')),
            ],
            8.6,
        );
    }

    public function testExecuteReturnsResponseWithMovie(): void
    {
        // Значение идентификатора фильма
        $movieId = 1;
        $movie = $this->createTestMovieWithId($movieId);

        // Создаем mock для IMovieRepository
        $movieRepositoryMock = $this->createMock(IMovieRepository::class);
        $movieRepositoryMock->expects($this->once())
            ->method('getById')
            ->with($movieId)
            ->willReturn($movie);

        // Создаем экземпляр use-case с mock-репозиторием
        $usecase = new GetMovieByIdUsecase($movieRepositoryMock);

        // Создаем запрос
        $request = new GetMovieByIdRequest($movieId);

        // Выполняем use-case
        $response = $usecase->execute($request);

        // Проверяем, что получен корректный ответ
        $this->assertInstanceOf(GetMovieByIdResponse::class, $response);
        $this->assertSame($movie, $response->movie);
    }

    public function testExecuteReturnsResponseWithNullWhenMovieNotFound(): void
    {
        $movieId = 999;

        // Создаем mock для IMovieRepository, который возвращает null
        $movieRepositoryMock = $this->createMock(IMovieRepository::class);
        $movieRepositoryMock->expects($this->once())
            ->method('getById')
            ->with($movieId)
            ->willReturn(null);

        // Создаем экземпляр use-case с mock-репозиторием
        $usecase = new GetMovieByIdUsecase($movieRepositoryMock);

        // Создаем запрос
        $request = new GetMovieByIdRequest($movieId);

        // Выполняем use-case
        $response = $usecase->execute($request);

        // Проверяем, что в ответе свойство movie равно null
        $this->assertInstanceOf(GetMovieByIdResponse::class, $response);
        $this->assertNull($response->movie);
    }
}

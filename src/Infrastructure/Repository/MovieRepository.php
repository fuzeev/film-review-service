<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Actor;
use App\Domain\Entity\Director;
use App\Domain\Entity\Genre;
use App\Domain\Entity\Movie;
use App\Domain\Enum\MovieSource;
use App\Domain\Repository\IMovieRepository;

class MovieRepository implements IMovieRepository
{
    public function getById(int $id): ?Movie
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
}

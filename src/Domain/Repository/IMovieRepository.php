<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Movie;

interface IMovieRepository
{
    public function getById(int $id): ?Movie;
}

<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Movie;

interface IMovieRepository
{
    public function getById(int $id): ?Movie;
}
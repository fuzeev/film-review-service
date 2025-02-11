<?php

declare(strict_types=1);

namespace App\Infrastructure\Converters;

use App\Domain\Entity\Genre as DomainGenre;
use App\Domain\Entity\Movie as DomainMovie;
use App\Infrastructure\Storage\Entity\Genre as DoctrineGenre;
use App\Infrastructure\Storage\Entity\Movie as DoctrineMovie;

class DoctrineConverter
{
    public function convertMovie(DoctrineMovie $value): DomainMovie
    {
    }

    public function convertGenre(DoctrineGenre $value): DomainGenre
    {
    }
}

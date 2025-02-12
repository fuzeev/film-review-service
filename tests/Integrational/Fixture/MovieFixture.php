<?php

namespace App\Tests\Integrational\Fixture;

use App\Infrastructure\Storage\Entity\Genre;
use App\Infrastructure\Storage\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MovieFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $genre = new Genre();
        $genre->setName('Триллер');
        $manager->persist($genre);

        $manager->flush();

        // Теперь можно получить ID сущности
        $genreId = $genre->getId();
        echo "Сгенерированный ID жанра: " . $genreId;

        // Если нужно использовать эту сущность в других фикстурах:
        $this->addReference('genre-thriller', $genre);
    }
}

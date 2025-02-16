<?php

declare(strict_types=1);

namespace DataFixtures;

use App\Infrastructure\Storage\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GenreFixture extends Fixture
{
    public const string THRILLER = 'genre-thriller';

    public const string CRIMINAL = 'genre-criminal';

    public const string DRAMA = 'genre-drama';

    public function load(ObjectManager $manager): void
    {
        $thriller = new Genre();
        $thriller->setName('Триллер');
        $manager->persist($thriller);

        $criminal = new Genre();
        $criminal->setName('Криминал');
        $manager->persist($criminal);

        $drama = new Genre();
        $drama->setName('Драма');
        $manager->persist($drama);

        $manager->flush();

        $this->addReference(self::THRILLER, $thriller);
        $this->addReference(self::CRIMINAL, $criminal);
        $this->addReference(self::DRAMA, $drama);
    }
}

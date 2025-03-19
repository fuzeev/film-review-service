<?php

declare(strict_types=1);

namespace DataFixtures;

use App\Infrastructure\Storage\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GenreFixture extends Fixture
{
    public const THRILLER = 'genre-thriller';
    public const CRIMINAL = 'genre-criminal';
    public const DRAMA = 'genre-drama';
    public const COMEDY = 'genre-comedy';
    public const HORROR = 'genre-horror';
    public const ACTION = 'genre-action';
    public const SCIFI = 'genre-sci-fi';
    public const ROMANCE = 'genre-romance';

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

        $comedy = new Genre();
        $comedy->setName('Комедия');
        $manager->persist($comedy);

        $horror = new Genre();
        $horror->setName('Ужасы');
        $manager->persist($horror);

        $action = new Genre();
        $action->setName('Боевик');
        $manager->persist($action);

        $scifi = new Genre();
        $scifi->setName('Научная фантастика');
        $manager->persist($scifi);

        $romance = new Genre();
        $romance->setName('Мелодрама');
        $manager->persist($romance);

        $manager->flush();

        $this->addReference(self::THRILLER, $thriller);
        $this->addReference(self::CRIMINAL, $criminal);
        $this->addReference(self::DRAMA, $drama);
        $this->addReference(self::COMEDY, $comedy);
        $this->addReference(self::HORROR, $horror);
        $this->addReference(self::ACTION, $action);
        $this->addReference(self::SCIFI, $scifi);
        $this->addReference(self::ROMANCE, $romance);
    }
}

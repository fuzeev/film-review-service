<?php

declare(strict_types=1);

namespace DataFixtures;

use App\Domain\Enum\MovieSource;
use App\Infrastructure\Storage\Entity\Actor;
use App\Infrastructure\Storage\Entity\Country;
use App\Infrastructure\Storage\Entity\Director;
use App\Infrastructure\Storage\Entity\Genre;
use App\Infrastructure\Storage\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MovieFixture extends Fixture implements DependentFixtureInterface
{
    public const string SCARFACE = 'movie-scarface';

    public function load(ObjectManager $manager): void
    {
        $scarface = new Movie();
        $scarface->setSource(MovieSource::MANUAL);
        $scarface->setTitle('Лицо со шрамом');
        $scarface->setDescription('Фильм с Аль Пачино');
        $scarface->setTitleOriginal('Scarface');
        $scarface->setYear(1985);
        $scarface->setRating(8.9);

        $scarface->addGenre($this->getReference(GenreFixture::DRAMA, Genre::class));
        $scarface->addGenre($this->getReference(GenreFixture::CRIMINAL, Genre::class));

        $scarface->setCountry($this->getReference(CountryFixture::USA, Country::class));

        $scarface->addActor($this->getReference(ActorFixture::AL_PACINO, Actor::class));

        $scarface->setDirector($this->getReference(DirectorFixture::BRIAN_DE_PALMA, Director::class));

        $manager->persist($scarface);
        $manager->flush();

        $this->addReference(self::SCARFACE, $scarface);
    }

    public function getDependencies(): array
    {
        return [ActorFixture::class, CountryFixture::class, GenreFixture::class, DirectorFixture::class];
    }
}

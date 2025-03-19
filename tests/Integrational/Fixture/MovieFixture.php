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
    public const SCARFACE = 'movie-scarface';
    public const GODFATHER = 'movie-godfather';
    public const PULP_FICTION = 'movie-pulp-fiction';
    public const INCEPTION = 'movie-inception';
    public const THE_SHINING = 'movie-the-shining';
    public const SCHINDLERS_LIST = 'movie-schindlers-list';

    public function load(ObjectManager $manager): void
    {
        // Фильм 1: Лицо со шрамом
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
        $this->addReference(self::SCARFACE, $scarface);

        // Фильм 2: Крестный отец
        $godfather = new Movie();
        $godfather->setSource(MovieSource::MANUAL);
        $godfather->setTitle('Крестный отец');
        $godfather->setDescription('Эпическая драма о мафии');
        $godfather->setTitleOriginal('The Godfather');
        $godfather->setYear(1972);
        $godfather->setRating(9.2);
        $godfather->addGenre($this->getReference(GenreFixture::DRAMA, Genre::class));
        $godfather->addGenre($this->getReference(GenreFixture::CRIMINAL, Genre::class));
        $godfather->setCountry($this->getReference(CountryFixture::USA, Country::class));
        $godfather->addActor($this->getReference(ActorFixture::AL_PACINO, Actor::class));
        $godfather->addActor($this->getReference(ActorFixture::PORECHENKOV, Actor::class));
        $godfather->setDirector($this->getReference(DirectorFixture::MARTIN_SCORSESE, Director::class));
        $manager->persist($godfather);
        $this->addReference(self::GODFATHER, $godfather);

        // Фильм 3: Криминальное чтиво
        $pulpFiction = new Movie();
        $pulpFiction->setSource(MovieSource::MANUAL);
        $pulpFiction->setTitle('Криминальное чтиво');
        $pulpFiction->setDescription('Культовый фильм Квентина Тарантино');
        $pulpFiction->setTitleOriginal('Pulp Fiction');
        $pulpFiction->setYear(1994);
        $pulpFiction->setRating(8.9);
        $pulpFiction->addGenre($this->getReference(GenreFixture::CRIMINAL, Genre::class));
        $pulpFiction->addGenre($this->getReference(GenreFixture::THRILLER, Genre::class));
        $pulpFiction->setCountry($this->getReference(CountryFixture::USA, Country::class));
        // Используем двух актеров для разнообразия
        $pulpFiction->addActor($this->getReference(ActorFixture::AL_PACINO, Actor::class));
        $pulpFiction->addActor($this->getReference(ActorFixture::BEZRUKOV, Actor::class));
        $pulpFiction->addActor($this->getReference(ActorFixture::GARMASH, Actor::class));
        $pulpFiction->setDirector($this->getReference(DirectorFixture::QUENTIN_TARANTINO, Director::class));
        $manager->persist($pulpFiction);
        $this->addReference(self::PULP_FICTION, $pulpFiction);

        // Фильм 4: Начало
        $inception = new Movie();
        $inception->setSource(MovieSource::MANUAL);
        $inception->setTitle('Начало');
        $inception->setDescription('Научно-фантастический боевик от Кристофера Нолана');
        $inception->setTitleOriginal('Inception');
        $inception->setYear(2010);
        $inception->setRating(8.8);
        $inception->addGenre($this->getReference(GenreFixture::SCIFI, Genre::class));
        $inception->addGenre($this->getReference(GenreFixture::ACTION, Genre::class));
        $inception->setCountry($this->getReference(CountryFixture::USA, Country::class));
        $inception->addActor($this->getReference(ActorFixture::BEZRUKOV, Actor::class));
        $inception->addActor($this->getReference(ActorFixture::KHABENSKY, Actor::class));
        $inception->setDirector($this->getReference(DirectorFixture::CHRISTOPHER_NOLAN, Director::class));
        $manager->persist($inception);
        $this->addReference(self::INCEPTION, $inception);

        // Фильм 5: Сияние
        $theShining = new Movie();
        $theShining->setSource(MovieSource::MANUAL);
        $theShining->setTitle('Сияние');
        $theShining->setDescription('Психологический хоррор от Стэнли Кубрика');
        $theShining->setTitleOriginal('The Shining');
        $theShining->setYear(1980);
        $theShining->setRating(8.4);
        $theShining->addGenre($this->getReference(GenreFixture::HORROR, Genre::class));
        $theShining->addGenre($this->getReference(GenreFixture::THRILLER, Genre::class));
        $theShining->setCountry($this->getReference(CountryFixture::USA, Country::class));
        $theShining->addActor($this->getReference(ActorFixture::MASHKOV, Actor::class));
        $theShining->setDirector($this->getReference(DirectorFixture::STANLEY_KUBRICK, Director::class));
        $manager->persist($theShining);
        $this->addReference(self::THE_SHINING, $theShining);

        // Фильм 6: Список Шиндлера
        $schindlersList = new Movie();
        $schindlersList->setSource(MovieSource::MANUAL);
        $schindlersList->setTitle('Список Шиндлера');
        $schindlersList->setDescription('Историческая драма о Второй мировой войне');
        $schindlersList->setTitleOriginal("Schindler's List");
        $schindlersList->setYear(1993);
        $schindlersList->setRating(9.0);
        $schindlersList->addGenre($this->getReference(GenreFixture::DRAMA, Genre::class));
        $schindlersList->addGenre($this->getReference(GenreFixture::CRIMINAL, Genre::class));
        $schindlersList->setCountry($this->getReference(CountryFixture::USA, Country::class));
        $schindlersList->addActor($this->getReference(ActorFixture::BEZRUKOV, Actor::class));
        $schindlersList->addActor($this->getReference(ActorFixture::LEONOV, Actor::class));
        $schindlersList->setDirector($this->getReference(DirectorFixture::STEVEN_SPIELBERG, Director::class));
        $manager->persist($schindlersList);
        $this->addReference(self::SCHINDLERS_LIST, $schindlersList);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ActorFixture::class,
            CountryFixture::class,
            GenreFixture::class,
            DirectorFixture::class,
        ];
    }
}

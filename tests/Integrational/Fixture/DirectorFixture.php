<?php

declare(strict_types=1);

namespace DataFixtures;

use App\Infrastructure\Storage\Entity\Director;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DirectorFixture extends Fixture
{
    public const BRIAN_DE_PALMA = 'brian-de-palma';
    public const STEVEN_SPIELBERG = 'steven-spielberg';
    public const MARTIN_SCORSESE = 'martin-scorsese';
    public const QUENTIN_TARANTINO = 'quentin-tarantino';
    public const CHRISTOPHER_NOLAN = 'christopher-nolan';
    public const STANLEY_KUBRICK = 'stanley-kubrick';

    public function load(ObjectManager $manager): void
    {
        $dePalma = new Director();
        $dePalma->setFirstName('Брайан');
        $dePalma->setLastName('де Пальма');
        $dePalma->setBirthday(new DateTimeImmutable('1975-01-01'));
        $manager->persist($dePalma);
        $this->addReference(self::BRIAN_DE_PALMA, $dePalma);

        $spielberg = new Director();
        $spielberg->setFirstName('Стивен');
        $spielberg->setLastName('Спилберг');
        $spielberg->setBirthday(new DateTimeImmutable('1946-12-18'));
        $manager->persist($spielberg);
        $this->addReference(self::STEVEN_SPIELBERG, $spielberg);

        $scorsese = new Director();
        $scorsese->setFirstName('Мартин');
        $scorsese->setLastName('Скорсезе');
        $scorsese->setBirthday(new DateTimeImmutable('1942-11-17'));
        $manager->persist($scorsese);
        $this->addReference(self::MARTIN_SCORSESE, $scorsese);

        $tarantino = new Director();
        $tarantino->setFirstName('Квентин');
        $tarantino->setLastName('Тарантино');
        $tarantino->setBirthday(new DateTimeImmutable('1963-03-27'));
        $manager->persist($tarantino);
        $this->addReference(self::QUENTIN_TARANTINO, $tarantino);

        $nolan = new Director();
        $nolan->setFirstName('Кристофер');
        $nolan->setLastName('Нолан');
        $nolan->setBirthday(new DateTimeImmutable('1970-07-30'));
        $manager->persist($nolan);
        $this->addReference(self::CHRISTOPHER_NOLAN, $nolan);

        $kubrick = new Director();
        $kubrick->setFirstName('Стэнли');
        $kubrick->setLastName('Кубрик');
        $kubrick->setBirthday(new DateTimeImmutable('1928-07-26'));
        $manager->persist($kubrick);
        $this->addReference(self::STANLEY_KUBRICK, $kubrick);

        $manager->flush();
    }
}

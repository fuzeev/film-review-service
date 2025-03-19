<?php

declare(strict_types=1);

namespace DataFixtures;

use App\Infrastructure\Storage\Entity\Actor;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ActorFixture extends Fixture
{
    public const string BEZRUKOV = 'bezrukov';
    public const string AL_PACINO = 'al_pacino';
    public const string MASHKOV = 'mashkov';
    public const string MENSHIKOV = 'menshikov';
    public const string KHABENSKY = 'khabensky';
    public const string SUKHANOV = 'sukhanov';
    public const string LEONOV = 'leonov';
    public const string MIKHALKOV = 'mikhalkov';
    public const string GARMASH = 'garmash';
    public const string BASILASHVILI = 'basilashvili';
    public const string PORECHENKOV = 'porechenkov';
    public const string KHAMATOVA = 'khamatova';

    public function load(ObjectManager $manager): void
    {
        $bezrukov = new Actor();
        $bezrukov->setFirstName('Сергей');
        $bezrukov->setLastName('Безруков');
        $bezrukov->setMiddleName('Иванович');
        $bezrukov->setBirthday(new DateTimeImmutable('1975-01-01'));
        $manager->persist($bezrukov);
        $this->addReference(self::BEZRUKOV, $bezrukov);

        $alPacino = new Actor();
        $alPacino->setFirstName('Аль');
        $alPacino->setLastName('Пачино');
        $alPacino->setBirthday(new DateTimeImmutable('1970-01-01'));
        $manager->persist($alPacino);
        $this->addReference(self::AL_PACINO, $alPacino);

        $mashkov = new Actor();
        $mashkov->setFirstName('Владимир');
        $mashkov->setLastName('Машков');
        $mashkov->setBirthday(new DateTimeImmutable('1960-05-27'));
        $manager->persist($mashkov);
        $this->addReference(self::MASHKOV, $mashkov);

        $menshikov = new Actor();
        $menshikov->setFirstName('Олег');
        $menshikov->setLastName('Меньшиков');
        $menshikov->setBirthday(new DateTimeImmutable('1960-01-08'));
        $manager->persist($menshikov);
        $this->addReference(self::MENSHIKOV, $menshikov);

        $khabensky = new Actor();
        $khabensky->setFirstName('Константин');
        $khabensky->setLastName('Хабенский');
        $khabensky->setBirthday(new DateTimeImmutable('1972-03-11'));
        $manager->persist($khabensky);
        $this->addReference(self::KHABENSKY, $khabensky);

        $sukhanov = new Actor();
        $sukhanov->setFirstName('Максим');
        $sukhanov->setLastName('Суханов');
        $sukhanov->setBirthday(new DateTimeImmutable('1965-07-15'));
        $manager->persist($sukhanov);
        $this->addReference(self::SUKHANOV, $sukhanov);

        $leonov = new Actor();
        $leonov->setFirstName('Евгений');
        $leonov->setLastName('Леонов');
        $leonov->setBirthday(new DateTimeImmutable('1926-01-02'));
        $manager->persist($leonov);
        $this->addReference(self::LEONOV, $leonov);

        $mikhalkov = new Actor();
        $mikhalkov->setFirstName('Никита');
        $mikhalkov->setLastName('Михалков');
        $mikhalkov->setBirthday(new DateTimeImmutable('1945-10-21'));
        $manager->persist($mikhalkov);
        $this->addReference(self::MIKHALKOV, $mikhalkov);

        $garmash = new Actor();
        $garmash->setFirstName('Сергей');
        $garmash->setLastName('Гармаш');
        $garmash->setBirthday(new DateTimeImmutable('1950-12-12'));
        $manager->persist($garmash);
        $this->addReference(self::GARMASH, $garmash);

        $basilashvili = new Actor();
        $basilashvili->setFirstName('Олег');
        $basilashvili->setLastName('Басилашвили');
        $basilashvili->setBirthday(new DateTimeImmutable('1934-03-20'));
        $manager->persist($basilashvili);
        $this->addReference(self::BASILASHVILI, $basilashvili);

        $porechenkov = new Actor();
        $porechenkov->setFirstName('Михаил');
        $porechenkov->setLastName('Пореченков');
        $porechenkov->setBirthday(new DateTimeImmutable('1969-04-14'));
        $manager->persist($porechenkov);
        $this->addReference(self::PORECHENKOV, $porechenkov);

        $khamatova = new Actor();
        $khamatova->setFirstName('Чулпан');
        $khamatova->setLastName('Хаматова');
        $khamatova->setBirthday(new DateTimeImmutable('1975-06-01'));
        $manager->persist($khamatova);
        $this->addReference(self::KHAMATOVA, $khamatova);

        $manager->flush();
    }
}

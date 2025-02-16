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

    public function load(ObjectManager $manager): void
    {
        $bezrukov = new Actor();
        $bezrukov->setFirstName('Сергей');
        $bezrukov->setLastName('Безруков');
        $bezrukov->setMiddleName('Иванович');
        $bezrukov->setBirthday(new DateTimeImmutable('1975-01-01'));
        $manager->persist($bezrukov);

        $alPacino = new Actor();
        $alPacino->setFirstName('Аль');
        $alPacino->setLastName('Пачино');
        $alPacino->setBirthday(new DateTimeImmutable('1970-01-01'));
        $manager->persist($alPacino);

        $manager->flush();

        $this->addReference(self::BEZRUKOV, $bezrukov);
        $this->addReference(self::AL_PACINO, $alPacino);
    }
}

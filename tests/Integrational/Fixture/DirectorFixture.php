<?php

declare(strict_types=1);

namespace DataFixtures;

use App\Infrastructure\Storage\Entity\Director;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DirectorFixture extends Fixture
{
    public const string BRIAN_DE_PALMA = 'brian-de-palma';

    public function load(ObjectManager $manager): void
    {
        $dePalma = new Director();
        $dePalma->setFirstName('Брайан');
        $dePalma->setLastName('де Пальма');
        $dePalma->setBirthday(new DateTimeImmutable('1975-01-01'));
        $manager->persist($dePalma);

        $manager->flush();

        $this->addReference(self::BRIAN_DE_PALMA, $dePalma);
    }
}

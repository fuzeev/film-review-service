<?php

declare(strict_types=1);

namespace DataFixtures;

use App\Infrastructure\Storage\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CountryFixture extends Fixture
{
    public const string RUSSIA = 'country-russia';

    public const string USA = 'country-usa';

    public function load(ObjectManager $manager): void
    {
        $russia = new Country();
        $russia->setName('Россия');
        $manager->persist($russia);

        $usa = new Country();
        $usa->setName('США');
        $manager->persist($usa);

        $manager->flush();

        $this->addReference(self::RUSSIA, $russia);
        $this->addReference(self::USA, $usa);
    }
}

<?php

declare(strict_types=1);

namespace DataFixtures;

use App\Infrastructure\Storage\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CountryFixture extends Fixture
{
    public const RUSSIA = 'country-russia';
    public const USA = 'country-usa';
    public const CANADA = 'country-canada';
    public const UK = 'country-uk';
    public const GERMANY = 'country-germany';
    public const FRANCE = 'country-france';
    public const ITALY = 'country-italy';

    public function load(ObjectManager $manager): void
    {
        $russia = new Country();
        $russia->setName('Россия');
        $manager->persist($russia);

        $usa = new Country();
        $usa->setName('США');
        $manager->persist($usa);

        $canada = new Country();
        $canada->setName('Канада');
        $manager->persist($canada);

        $uk = new Country();
        $uk->setName('Великобритания');
        $manager->persist($uk);

        $germany = new Country();
        $germany->setName('Германия');
        $manager->persist($germany);

        $france = new Country();
        $france->setName('Франция');
        $manager->persist($france);

        $italy = new Country();
        $italy->setName('Италия');
        $manager->persist($italy);

        $manager->flush();

        $this->addReference(self::RUSSIA, $russia);
        $this->addReference(self::USA, $usa);
        $this->addReference(self::CANADA, $canada);
        $this->addReference(self::UK, $uk);
        $this->addReference(self::GERMANY, $germany);
        $this->addReference(self::FRANCE, $france);
        $this->addReference(self::ITALY, $italy);
    }
}

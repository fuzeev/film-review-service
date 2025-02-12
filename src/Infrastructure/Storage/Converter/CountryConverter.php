<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\Converter;

use App\Domain\Entity\Country as DomainCountry;
use App\Infrastructure\Storage\Entity\Country as DoctrineCountry;
use App\Infrastructure\Storage\Exception\FailedToConvertException;
use Doctrine\ORM\EntityManagerInterface;

readonly class CountryConverter
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function doctrineToDomain(?DoctrineCountry $country): ?DomainCountry
    {
        if ($country === null) {
            return null;
        }

        $id = $country->getId();
        $name = $country->getName();

        if ($id === null || $name === null) {
            throw new FailedToConvertException(DoctrineCountry::class, DomainCountry::class);
        }

        return new DomainCountry($id, $name);
    }

    public function domainToDoctrine(?DomainCountry $country): ?DoctrineCountry
    {
        if ($country === null) {
            return null;
        }

        /** @var DoctrineCountry $entity */
        $entity = $this->entityManager->getReference(DoctrineCountry::class, $country->id);
        $entity->setName($country->name);

        return $entity;
    }
}

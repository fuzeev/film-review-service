<?php

namespace App\Infrastructure\Storage\Converter;

use App\Domain\Entity\Country as DomainCountry;
use App\Infrastructure\Storage\Entity\Country as DoctrineCountry;
use Doctrine\ORM\EntityManagerInterface;

readonly class CountryConverter
{
    public function __construct(
       private EntityManagerInterface $entityManager
    ) {
           }


    public function doctrineToDomain(DoctrineCountry $country): DomainCountry
    {
        return new DomainCountry(
            $country->getId(),
            $country->getName(),
        );
    }

    public function domainToDoctrine(DomainCountry $country): DoctrineCountry
    {
        $entity = $this->entityManager->getReference(DoctrineCountry::class, $country->id);
        $entity->setName($country->name);
        return $entity;
    }
}

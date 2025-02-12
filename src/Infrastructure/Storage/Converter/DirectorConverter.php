<?php

namespace App\Infrastructure\Storage\Converter;

use App\Domain\Entity\Director as DomainDirector;
use App\Infrastructure\Storage\Entity\Director as DoctrineDirector;
use Doctrine\ORM\EntityManagerInterface;

readonly class DirectorConverter
{
    public function __construct(
       private EntityManagerInterface $entityManager
    ) {
           }


    public function doctrineToDomain(DoctrineDirector $director): DomainDirector
    {
        return new DomainDirector(
            $director->getId(),
            $director->getFirstName(),
            $director->getLastName(),
            $director->getMiddleName(),
            $director->getBirthday(),
        );
    }

    public function domainToDoctrine(DomainDirector $director): DoctrineDirector
    {
        $entity = $this->entityManager->getReference(DoctrineDirector::class, $director->id);
        $entity->setFirstName($director->firstName);
        $entity->setLastName($director->lastName);
        $entity->setBirthday($director->birthday);

        return $entity;
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\Converter;

use App\Domain\Entity\Director as DomainDirector;
use App\Infrastructure\Storage\Entity\Director as DoctrineDirector;
use App\Infrastructure\Storage\Exception\FailedToConvertException;
use Doctrine\ORM\EntityManagerInterface;

readonly class DirectorConverter
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function doctrineToDomain(?DoctrineDirector $director): ?DomainDirector
    {
        if ($director === null) {
            return null;
        }

        $id = $director->getId();
        $firstName = $director->getFirstName();
        $lastName = $director->getLastName();
        $middleName = $director->getMiddleName(); // Может быть null, если это допустимо
        $birthday = $director->getBirthday();

        if ($id === null || $firstName === null || $lastName === null || $birthday === null) {
            throw new FailedToConvertException(DoctrineDirector::class, DomainDirector::class);
        }

        return new DomainDirector($id, $firstName, $lastName, $middleName, $birthday);
    }

    public function domainToDoctrine(?DomainDirector $director): ?DoctrineDirector
    {
        if ($director === null) {
            return null;
        }

        /** @var DoctrineDirector $entity */
        $entity = $this->entityManager->getReference(DoctrineDirector::class, $director->id);
        $entity->setFirstName($director->firstName);
        $entity->setLastName($director->lastName);
        $entity->setMiddleName($director->middleName);
        $entity->setBirthday($director->birthday);

        return $entity;
    }
}

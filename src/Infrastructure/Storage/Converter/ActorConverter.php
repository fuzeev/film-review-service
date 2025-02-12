<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\Converter;

use App\Domain\Entity\Actor as DomainActor;
use App\Infrastructure\Storage\Entity\Actor as DoctrineActor;
use App\Infrastructure\Storage\Exception\FailedToConvertException;
use Doctrine\ORM\EntityManagerInterface;

readonly class ActorConverter
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function doctrineToDomain(?DoctrineActor $actor): ?DomainActor
    {
        if ($actor === null) {
            return null;
        }

        $id = $actor->getId();
        $firstName = $actor->getFirstName();
        $lastName = $actor->getLastName();
        $birthday = $actor->getBirthday();
        $middleName = $actor->getMiddleName();

        if ($id === null
            || $firstName === null
            || $lastName === null
            || $birthday === null
        ) {
            throw new FailedToConvertException(DoctrineActor::class, DomainActor::class);
        }

        return new DomainActor($id, $firstName, $lastName, $middleName, $birthday);
    }

    public function domainToDoctrine(?DomainActor $actor): ?DoctrineActor
    {
        if ($actor === null) {
            return null;
        }

        /** @var DoctrineActor $entity */
        $entity = $this->entityManager->getReference(DoctrineActor::class, $actor->id);
        $entity->setFirstName($actor->firstName);
        $entity->setLastName($actor->lastName);
        $entity->setBirthday($actor->birthday);

        return $entity;
    }
}

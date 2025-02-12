<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\Converter;

use App\Domain\Entity\Actor as DomainActor;
use App\Infrastructure\Storage\Entity\Actor as DoctrineActor;
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

        return new DomainActor(
            $actor->getId(),
            $actor->getFirstName(),
            $actor->getLastName(),
            $actor->getMiddleName(),
            $actor->getBirthday(),
        );
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

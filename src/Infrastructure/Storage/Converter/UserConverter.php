<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\Converter;

use App\Domain\Entity\User as DomainUser;
use App\Domain\Enum\UserRole;
use App\Infrastructure\Storage\Entity\User as DoctrineUser;
use App\Infrastructure\Storage\Exception\FailedToConvertException;
use Doctrine\ORM\EntityManagerInterface;

readonly class UserConverter
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function doctrineToDomain(?DoctrineUser $user): ?DomainUser
    {
        if ($user === null) {
            return null;
        }

        $id = $user->getId();
        $firstName = $user->getFirstName();
        $lastName = $user->getLastName();
        $middleName = $user->getMiddleName(); // Может быть null, если это допустимо
        $birthday = $user->getBirthday();
        $email = $user->getEmail();
        $username = $user->getUsername();
        $role = UserRole::tryFrom($user->getRole() ?? '');

        if ($id === null || $firstName === null || $lastName === null || $birthday === null || $email === null
            || $username === null || $role === null) {
            throw new FailedToConvertException(DomainUser::class, DoctrineUser::class);
        }

        return new DomainUser($id, $firstName, $lastName, $middleName, $birthday, $email, $username, $role);
    }

    public function domainToDoctrine(?DomainUser $user): ?DoctrineUser
    {
        if ($user === null) {
            return null;
        }

        /** @var DoctrineUser $entity */
        $entity = $this->entityManager->getReference(DoctrineUser::class, $user->id);
        $entity->setFirstName($user->firstName);
        $entity->setLastName($user->lastName);
        $entity->setLastName($user->lastName);
        $entity->setBirthday($user->birthday);
        $entity->setEmail($user->email);
        $entity->setUsername($user->username);
        $entity->setRole($user->role->value);

        return $entity;
    }
}

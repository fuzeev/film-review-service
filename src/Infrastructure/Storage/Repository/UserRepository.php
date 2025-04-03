<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\Repository;

use App\Domain\Dto\CreateUserDto;
use App\Domain\Entity\User as DomainUser;
use App\Domain\Repository\IUserRepository;
use App\Infrastructure\Storage\Converter\UserConverter;
use App\Infrastructure\Storage\Entity\User as DoctrineUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @extends ServiceEntityRepository<DoctrineUser>
 */
class UserRepository extends ServiceEntityRepository implements IUserRepository
{
    use EntityRepositoryTrait;
    public function __construct(
        ManagerRegistry $registry,
        protected UserConverter $converter,
        protected UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct($registry, DoctrineUser::class);
    }

    public function getById(int $id): ?DomainUser
    {
        $model = $this->find($id);

        return $this->converter->doctrineToDomain($model);
    }

    public function findByEmail(string $email): ?DomainUser
    {
        $model = $this->findOneBy([
            'email' => $email,
        ]);

        return $this->converter->doctrineToDomain($model);
    }

    public function findByUsername(string $username): ?DomainUser
    {
        $model = $this->findOneBy([
            'username' => $username,
        ]);

        return $this->converter->doctrineToDomain($model);
    }


    public function createUser(CreateUserDto $dto): DomainUser
    {
        $doctrineUser = new DoctrineUser();
        $doctrineUser->setFirstName($dto->firstName)
            ->setLastName($dto->lastName)
            ->setMiddleName($dto->middleName)
            ->setBirthday($dto->birthday)
            ->setEmail($dto->email)
            ->setUsername($dto->username)
            ->setRole($dto->role->value);

        $hashedPassword = $this->passwordHasher->hashPassword($doctrineUser, $dto->password);
        $doctrineUser->setPassword($hashedPassword);

        $em = $this->getEntityManager();
        $em->persist($doctrineUser);
        $em->flush();

        return $this->converter->doctrineToDomain($doctrineUser);
    }
}

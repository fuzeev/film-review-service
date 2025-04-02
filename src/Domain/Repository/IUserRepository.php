<?php

namespace App\Domain\Repository;

use App\Domain\Dto\CreateUserDto;
use App\Domain\Entity\User;

interface IUserRepository extends IEntityRepository
{
    /**
     * Создает юзера. Если удалось, возвращает доменную модель.
     * Если не удалось, бросает исключение.
     */
    public function createUser(CreateUserDto $dto): User;

    /**
     * Находит пользователя по username
     *
     * @param string $username
     * @return User|null
     */
    public function findByUsername(string $username): ?User;

    /**
     * Находит пользователя по email
     *
     * @param string $username
     * @return User|null
     */
    public function findByEmail(string $email): ?User;
}

<?php

declare(strict_types=1);

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
     */
    public function findByUsername(string $username): ?User;

    /**
     * Находит пользователя по email
     */
    public function findByEmail(string $email): ?User;

    public function getById(int $id): ?User;
}

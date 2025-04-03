<?php

declare(strict_types=1);

namespace App\Application\Usecase;

use App\Application\Dto\CreateAccountRequest;
use App\Application\Dto\CreateAccountResult;
use App\Domain\Dto\CreateUserDto;
use App\Domain\Enum\UserRole;
use App\Domain\Repository\IUserRepository;
use DateTimeImmutable;

class CreateAccountUsecase
{
    public function __construct(
        protected IUserRepository $userRepository
    ) {
    }

    public function execute(CreateAccountRequest $request): CreateAccountResult
    {
        $errors = [];

        if ($request->password !== $request->confirmPassword) {
            $errors['confirmPassword'] = "Пароли не совпадают";
            return CreateAccountResult::error($errors);
        }

        if ($this->userRepository->findByUsername($request->username)) {
            $errors['username'] = "Пользователь с ником $request->username уже зарегистрирован";
        }

        if ($this->userRepository->findByEmail($request->email)) {
            $errors['email'] = "Пользователь с email $request->email уже зарегистрирован";
        }

        if ($errors) {
            return CreateAccountResult::error($errors);
        }

        $dto = new CreateUserDto(
            firstName: $request->firstName,
            lastName: $request->lastName,
            middleName: $request->middleName,
            birthday: DateTimeImmutable::createFromFormat('Y-m-d', $request->birthday),
            email: $request->email,
            username: $request->username,
            role: UserRole::REWIEWER,
            password: $request->password
        );

        $model = $this->userRepository->createUser($dto);

        return CreateAccountResult::success($model->id);
    }
}

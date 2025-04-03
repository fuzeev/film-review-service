<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Application\Dto\CreateAccountRequest;
use App\Application\Usecase\CreateAccountUsecase;
use JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProfileController extends AbstractController
{
    public function __construct(
        protected CreateAccountUsecase $createAccountUsecase,
        protected ValidatorInterface $validator,
    ) {
    }

    #[Route('/create-account', name: 'create_account', methods: ['POST'])]
    public function getMovieListHandler(Request $request): Response
    {
        try {
            $requestDecoded = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return $this->json('Invalid Json', 400);
        }

        $dto = new CreateAccountRequest(
            firstName: $requestDecoded['firstName'] ?? null,
            lastName: $requestDecoded['lastName'] ?? null,
            middleName: $requestDecoded['middleName'] ?? null,
            birthday: $requestDecoded['birthday'] ?? null,
            email: $requestDecoded['email'] ?? null,
            username: $requestDecoded['username'] ?? null,
            password: $requestDecoded['password'] ?? null,
            confirmPassword: $requestDecoded['confirmPassword'] ?? null,
        );

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return $this->validationErrorResponse($errors);
        }

        $result = $this->createAccountUsecase->execute($dto);

        if (! $result->success) {
            return $this->json($result->errors, Response::HTTP_BAD_REQUEST);
        }

        return $this->json($result, Response::HTTP_CREATED);
    }
}

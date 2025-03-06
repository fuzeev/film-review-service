<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class AbstractController extends SymfonyAbstractController
{
    public function validationErrorResponse(ConstraintViolationListInterface $constraintViolationList): JsonResponse
    {
        $errors = [];
        foreach ($constraintViolationList as $constraintViolation) {
            $errors[] = [
                'field' => $constraintViolation->getPropertyPath(),
                'error' => $constraintViolation->getMessage(),
            ];
        }

        return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}

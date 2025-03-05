<?php

namespace App\Presentation\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;

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

//        return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        return $this->json($errors, 418);
    }
}

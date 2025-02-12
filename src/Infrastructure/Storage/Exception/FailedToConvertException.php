<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\Exception;

use Exception;

class FailedToConvertException extends Exception
{
    public function __construct(string $inputClassName, string $resultClassName, string $reason = '')
    {
        $message = "Не удалось создать {$resultClassName} из {$inputClassName}.";

        if (! empty($reason)) {
            $message .= " Причина: {$reason}.";
        }

        parent::__construct($message);
    }
}

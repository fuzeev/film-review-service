<?php

declare(strict_types=1);

namespace App\Domain\Entity;

class Country
{
    public function __construct(
        public string $id,
        public string $name,
    ) {
    }
}

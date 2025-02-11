<?php

declare(strict_types=1);

namespace App\Domain\Entity;

class Country
{
    public function __construct(
        public int $id,
        public string $name,
    ) {
    }
}

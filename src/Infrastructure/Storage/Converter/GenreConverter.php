<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\Converter;

use App\Domain\Entity\Genre as DomainGenre;
use App\Infrastructure\Storage\Entity\Genre as DoctrineGenre;
use Doctrine\ORM\EntityManagerInterface;

readonly class GenreConverter
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function doctrineToDomain(DoctrineGenre $genre): DomainGenre
    {
        return new DomainGenre($genre->getId(), $genre->getName());
    }

    public function domainToDoctrine(DomainGenre $genre): DoctrineGenre
    {
        $entity = $this->entityManager->getReference(DoctrineGenre::class, $genre->id);
        $entity->setName($genre->name);

        return $entity;
    }
}

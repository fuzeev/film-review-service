<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\Repository;

use App\Domain\Repository\ICountryRepository;
use App\Infrastructure\Storage\Converter\CountryConverter;
use App\Infrastructure\Storage\Entity\Country as DoctrineCountry;
use App\Domain\Entity\Country as DomainCountry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Country>
 */
class CountryRepository extends ServiceEntityRepository implements ICountryRepository
{
    use EntityRepositoryTrait;

    public function __construct(
        ManagerRegistry $registry,
        protected CountryConverter $converter,
    )
    {
        parent::__construct($registry, DoctrineCountry::class);
    }

    public function getById(int $id): ?DomainCountry
    {
        $model = $this->find($id);
        return $this->converter->doctrineToDomain($model);
    }
}

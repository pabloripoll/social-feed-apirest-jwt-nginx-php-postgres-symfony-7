<?php

namespace App\Domain\Geo\Repository;

use App\Domain\Geo\Entity\GeoRegion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GeoRegion>
 *
 *  try DBAL query
 */
class GeoRegionRepository extends ServiceEntityRepository
{
    protected $db;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GeoRegion::class);

        $this->db = $this->getEntityManager()->getConnection();
    }

    /**
     * Find the id of a region by its name and the continent's name.
     */
    public function findIdByName(string $name): ?int
    {
        $sql = 'SELECT id FROM geo_regions WHERE name = :name LIMIT 1';
        $id = $this->db->fetchOne($sql, ['name' => $name]);

        return $id === false ? null : (int) $id;
    }

    /**
     * Find the id of a region by its name and the continent's name.
     */
    public function regionByContinentNameAndRegionName(string $continentName, string $regionName): ?GeoRegion
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.continent', 'c')
            ->andWhere('c.name = :continentName')
            ->andWhere('r.name = :regionName')
            ->setParameter('continentName', $continentName)
            ->setParameter('regionName', $regionName)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

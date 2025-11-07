<?php

namespace App\Domain\Geo\Repository;

use App\Domain\Geo\Entity\GeoContinent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GeoContinent>
 *
 * try DBAL query
 */
class GeoContinentRepository extends ServiceEntityRepository
{
    protected $db;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GeoContinent::class);

        $this->db = $this->getEntityManager()->getConnection();
    }

    /**
     * Find the id of a region by its name and the continent's name.
     */
    public function findIdByName(string $name): ?int
    {
        $sql = 'SELECT id FROM geo_continents WHERE name = :name LIMIT 1';
        $id = $this->db->fetchOne($sql, ['name' => $name]);

        return $id === false ? null : (int) $id;
    }
}

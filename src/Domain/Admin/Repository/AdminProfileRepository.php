<?php

namespace App\Domain\Admin\Repository;

use App\Domain\Admin\Entity\AdminProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AdminProfile>
 *
 * @method AdminProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdminProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdminProfile[]    findAll()
 * @method AdminProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdminProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdminProfile::class);
    }
}

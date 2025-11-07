<?php

namespace App\Domain\Admin\Repository;

use App\Domain\Admin\Entity\AdminAccessLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AdminAccessLog>
 *
 * @method AdminAccessLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdminAccessLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdminAccessLog[]    findAll()
 * @method AdminAccessLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdminAccessLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdminAccessLog::class);
    }
}
<?php

namespace App\Domain\Member\Repository;

use App\Domain\Member\Entity\MemberAccessLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MemberAccessLog>
 *
 * @method MemberAccessLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method MemberAccessLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method MemberAccessLog[]    findAll()
 * @method MemberAccessLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemberAccessLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MemberAccessLog::class);
    }
}
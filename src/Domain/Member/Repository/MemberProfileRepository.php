<?php

namespace App\Domain\Member\Repository;

use App\Domain\Member\Entity\MemberProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MemberProfile>
 *
 * @method MemberProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method MemberProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method MemberProfile[]    findAll()
 * @method MemberProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemberProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MemberProfile::class);
    }
}

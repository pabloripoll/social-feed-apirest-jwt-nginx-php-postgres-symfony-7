<?php

namespace App\Domain\Member\Repository;

use App\Domain\Member\Entity\MemberActivationCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MemberActivationCode>
 *
 * @method MemberActivationCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method MemberActivationCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method MemberActivationCode[]    findAll()
 * @method MemberActivationCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemberActivationCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MemberActivationCode::class);
    }
}

<?php

namespace App\Repository;

use Doctrine\ORM\Query;
use App\Entity\Campaign;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Campaign>
 *
 * @method Campaign|null find($id, $lockMode = null, $lockVersion = null)
 * @method Campaign|null findOneBy(array $criteria, array $orderBy = null)
 * @method Campaign[]    findAll()
 * @method Campaign[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CampaignRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private Security $security)
    {
        parent::__construct($registry, Campaign::class);
    }

    public function add(Campaign $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Campaign $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function queryAll(): Query|null
    {
        return $this->createQueryBuilder('c')
            ->where('c.ownedBy = :user')
            ->setParameter('user', $this->security->getUser())
            ->join('c.company', 'company')
            ->orderBy('company.name', 'ASC')
            ->getQuery();
    }

    public function queryAllActive(): Query|null
    {
        return $this->createQueryBuilder('c')
            ->where('c.ownedBy = :user')
            ->andWhere('c.endedAt IS NOT NULL')
            ->setParameter('user', $this->security->getUser())
            ->join('c.company', 'company')
            ->orderBy('company.name', 'ASC')
            ->getQuery();
    }

    //    /**
    //     * @return Campaign[] Returns an array of Campaign objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Campaign
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

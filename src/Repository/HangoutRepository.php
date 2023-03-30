<?php

namespace App\Repository;

use App\Entity\Hangout;
use App\Entity\User;
use App\Form\HangoutFilterType;
use App\Form\Model\HangoutFilterTypeModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\Date;


/**
 * @extends ServiceEntityRepository<Hangout>
 *
 * @method Hangout|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hangout|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hangout[]    findAll()
 * @method Hangout[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HangoutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hangout::class);
    }

    public function save(Hangout $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Hangout $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function filterResults(HangoutFilterTypeModel $queryParams): array
    {
        //TODO fix orWhere clauses -> use ifX, expr() groups
        $userId = $queryParams->userId;
        $qb = $this->createQueryBuilder('h');
        $qb->leftJoin('h.participants', 'u');
        $qb->leftJoin('h.site', 's');
        $qb->andWhere('h.site = :siteId');
        $qb->setParameter('siteId', $queryParams->site->getId());
        if ($queryParams->from) {
            $qb->setParameter('from', $queryParams->from);
        } else {
            $qb->setParameter('from', new \DateTime());
        }
        if ($queryParams->to) {
            $qb->setParameter('to', $queryParams->to);
        } else {
            $qb->setParameter('to', new \DateTime('2027-01-01'));
        }
        $qb->andWhere('h.startTimestamp >= :from AND h.startTimestamp <= :to');
        if ($queryParams->searchQuery) {
            $qb->andWhere('h.name LIKE :query');
            $qb->setParameter('query', '%' . $queryParams->searchQuery . '%');
        }
        if($queryParams->isSubscribed || $queryParams->isNotSubscribed || $queryParams->isOrganizer) $qb->setParameter('userId', $userId); echo "test";
        if($queryParams->isOrganizer) $qb->andWhere('h.creator = :userId');
        if($queryParams->isSubscribed)  $qb->andWhere('u.id = :userId');
        if($queryParams->isNotSubscribed) $qb->andWhere('u.id != :userId');
        if($queryParams->isExpired){
            $qb->andWhere('h.startTimestamp < CURRENT_TIMESTAMP()');
        } else{
            $qb->andWhere('h.startTimestamp > CURRENT_TIMESTAMP()');
        }
        $query = $qb->getQuery();
        var_dump($query->getDQL());
        return $query->getResult();
    }



//    /**
//     * @return Hangout[] Returns an array of Hangout objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Hangout
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

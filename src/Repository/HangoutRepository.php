<?php

namespace App\Repository;

use App\Entity\Hangout;
use App\Entity\User;
use App\Form\HangoutFilterType;
use App\Form\Model\HangoutFilterTypeModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


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
            $date = new \DateTime();
            date_sub($date, new \DateInterval('P30D'));
            $qb->setParameter('from', $date);
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
        if($queryParams->isSubscribed || $queryParams->isNotSubscribed || $queryParams->isOrganizer) $qb->setParameter('userId', $userId);
        $queryGroupOr = $qb->expr()->orX();
        if($queryParams->isOrganizer) $queryGroupOr->add($qb->expr()->eq('h.creator',':userId'));
        if($queryParams->isSubscribed)  $queryGroupOr->add($qb->expr()->eq('u.id',':userId'));
        if($queryParams->isNotSubscribed) $queryGroupOr->add($qb->expr()->neq('u.id',':userId'));
        if($queryParams->isExpired){
            $date = new \DateTime();
            $date = date_sub($date, new \DateInterval('P31D'));
            $queryGroupOr->add( $qb->expr()->lt('h.startTimestamp',$date->format('Y-m-d')));
        } else{
            $qb->andWhere('h.startTimestamp > CURRENT_TIMESTAMP()');
        }
        if($queryGroupOr->count() > 0) $qb->andWhere($queryGroupOr);
        $query = $qb->getQuery();
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

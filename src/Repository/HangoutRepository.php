<?php

namespace App\Repository;

use App\Entity\Hangout;
use App\Entity\User;
use App\Form\HangoutFilterType;
use App\Form\Model\HangoutFilterTypeModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Integer;
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
        $userId = $queryParams->userId;
        $qb = $this->createQueryBuilder('h');
        $qb->leftJoin('h.participants', 'u');
        $qb->leftJoin('h.site', 's');
        //First condition : limit by Site
        $qb->andWhere('h.site = :siteId');
        $qb->setParameter('siteId', $queryParams->site->getId());
        if ($queryParams->from) {
            $qb->setParameter('from', $queryParams->from);
        }
        //if no $to present, set high clamp to DateTime MAX for a 32 bit system
        if ($queryParams->to) {
            $qb->setParameter('to', $queryParams->to);
        } else {
            $date = new \DateTime();
            $date->setTimestamp(2147583648);
            $qb->setParameter('to', $date);
        }
        //apply DateTime clamp
        $qb->andWhere('h.startTimestamp >= :from AND h.startTimestamp <= :to');
        //apply search query such as name LIKE %query%
        if ($queryParams->searchQuery) {
            $qb->andWhere('h.name LIKE :query');
            $qb->setParameter('query', '%' . $queryParams->searchQuery . '%');
        }
        //only params requiring userId
        if($queryParams->isSubscribed || $queryParams->isNotSubscribed || $queryParams->isOrganizer) $qb->setParameter('userId', $userId);
        //declare a grouped condition / orX
        $queryGroupOr = $qb->expr()->orX();
        //add filters conditionally to group such as $isOrganizer || $isSubscribed || $isNotSubscribed || $isExpired
        if($queryParams->isOrganizer) $queryGroupOr->add($qb->expr()->eq('h.creator',':userId'));
        if($queryParams->isSubscribed)  $queryGroupOr->add($qb->expr()->eq('u.id',':userId'));
        if($queryParams->isNotSubscribed) $queryGroupOr->add($qb->expr()->neq('u.id',':userId'));
        // allows displaying Hangouts where start date > (now - 30 days)
        $date = new \DateTime();
        $qb->andWhere('h.startTimestamp >= :filterDate');
        if($queryParams->isExpired) {
            date_sub($date, new \DateInterval('P30D'));
        }
        $qb->setParameter('filterDate', $date);
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

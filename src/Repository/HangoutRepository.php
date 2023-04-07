<?php

namespace App\Repository;

use App\Entity\Hangout;
use App\Form\Model\HangoutFilterTypeModel;
use App\TypeConstraints\StateConstraints;
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
        $user = $queryParams->user;
        $qb = $this->createQueryBuilder('h')
        //First condition : limit by Site
        ->andWhere('h.site = :site')
        ->setParameter('site', $queryParams->site);
        if ($queryParams->from) {
            $qb->setParameter('from', $queryParams->from);
        }else{
            $date = new \DateTime();
            $date->sub(new \DateInterval('P30D'));
            $qb->setParameter('from', $date);
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
            $qb->andWhere('h.name LIKE :query')
            ->setParameter('query', '%' . $queryParams->searchQuery . '%');
        }
        //only params requiring userId
        if($queryParams->isSubscribed || $queryParams->isNotSubscribed || $queryParams->isOrganizer) $qb->setParameter('user', $user);
        //declare a grouped condition / orX
        //add filters conditionally to group such as $isOrganizer || $isSubscribed || $isNotSubscribed || $isExpired
        $orxGroup = $qb->expr()->orX();
        if($queryParams->isOrganizer === true) {
            $orxGroup->add('h.creator = :user');
        }
        if($queryParams->isSubscribed === true){
            $orxGroup->add(':user MEMBER OF h.participants');
        }
        if($queryParams->isNotSubscribed === true){
            $orxGroup->add('NOT(:user MEMBER OF h.participants)');
        }
        $qb->andWhere($orxGroup);
//        // allows displaying Hangouts where start date > (now - 30 days)
        $date = new \DateTime();
        $qb->andWhere('h.startTimestamp >= :filterDate');
        if(!$queryParams->isExpired === true) {
            $date = $date->sub(new \DateInterval('P30D'));
        }
        $qb->setParameter('filterDate', $date);
        $query = $qb->getQuery();
        $results = $query->getResult();
        return $results;
    }



    /**
     * @return Hangout[] Returns an array of Hangout objects
     */
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.creator = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

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

<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class procedureRepository extends EntityRepository
{

    public function stockHangout($dateNow,$dateEndHangout){

        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('CALL stock_hangout(:dateEndHangout, :dateNow)');
        $query->setParameter('dateEndHangout', $dateEndHangout);
        $query->setParameter('dateNow', $dateNow);
        return $query->getResult();


    }

}
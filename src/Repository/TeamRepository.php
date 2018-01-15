<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class TeamRepository extends EntityRepository
{

    public function findStanding() {
        return $this->createQueryBuilder('s')
            ->getQuery()
            ->getArrayResult();
    }
}

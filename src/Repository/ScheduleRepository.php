<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class ScheduleRepository extends EntityRepository
{

    public function findSchedule() {
        return $this->createQueryBuilder('s')
            ->getQuery()
            ->getArrayResult();
    }
}

<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class TournamentRepository extends EntityRepository
{
    public function findAllTodayTournamentsByUser(User $user) {
        $list = $this->createQueryBuilder('t')
            ->where('t.creator = :user')
            ->andWhere('t.date > CURRENT_DATE()')
            ->setParameter(':user', $user->getEmail())
            ->getQuery()
            ->getResult();
        $retList = array();
        foreach ($list as $val) {
            $date = new \DateTime();
            $date->setTime(0, 0, 0);
            if ($val->getDate()->diff($date)->days == 0) {
                $retList[] = $val;
            }
        }
        return $retList;
    }

    public function findAllPlanedTournamentsByUser(User $user) {
        $list =  $this->createQueryBuilder('t')
            ->where('t.creator = :user')
            ->andWhere('t.date > CURRENT_DATE()')
            ->setParameter(':user', $user->getEmail())
            ->getQuery()
            ->getResult();

        $retList = array();
        foreach ($list as $val) {
            if ($val->getDate()->diff(new \DateTime())->days > 0) {
                $retList[] = $val;
            }
        }
        return $retList;
    }

    public function findAllActiveTournamentsByUser(User $user) {
        return $this->createQueryBuilder('t')
            ->where('t.creator = :user')
            ->andWhere('t.date >= CURRENT_TIMESTAMP()')
            ->setParameter(':user', $user->getEmail())
            ->getQuery()
            ->getArrayResult();
    }

    public function findAllTournamentsByHash($hash) {
        return $this->createQueryBuilder('t')
            ->where('t.hash = :hash')
            ->andWhere('t.date >= CURRENT_TIMESTAMP()')
            ->setParameter(':hash', $hash)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

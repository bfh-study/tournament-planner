<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class TournamentRepository extends EntityRepository
{

    public function findAllTournamentsByUser(User $user) {
        return $this->createQueryBuilder('t')
            ->where('t.creator = :user')
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

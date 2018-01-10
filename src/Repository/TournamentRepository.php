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
}

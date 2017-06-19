<?php

namespace CoreBundle\Repository;

use CoreBundle\Entity\Employee;
use Doctrine\ORM\EntityRepository;
use LoginBundle\Entity\User;

class RegistrationRepository extends EntityRepository
{

    public function findActivates(User $user) {
        $sql = $this->createQueryBuilder('n');
        $sql
            ->innerJoin('n.employee', 'e')
            ->andWhere('e.user = :user')
            ->andWhere('e.active = true')
            ->setParameter('user', $user);

        $query = $sql->getQuery();

        return $query->getResult();
    }

}
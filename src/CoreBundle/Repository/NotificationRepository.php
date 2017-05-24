<?php

namespace CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;


class NotificationRepository extends EntityRepository
{

    public function findActivated($user) {
        $sql = $this->createQueryBuilder('n');
        $sql
            ->andWhere('n.active = true')
            ->andWhere('n.user = :user')
            ->innerJoin('n.employee', 'e')
            ->andWhere('e.active = true')
            ->setParameter('user', $user);

        $query = $sql->getQuery();

        return $query->getResult();
    }

    public function findActivatedByEmployeeId($employe) {
        $sql = $this->createQueryBuilder('n');
        $sql
            ->andWhere('n.active = true')
            ->innerJoin('n.employee', 'e')
            ->andWhere('e.active = true')
            ->andWhere('n.employee = :employee')
            ->setParameter('employee', $employe);

        $query = $sql->getQuery();

        return $query->getResult();
    }

    public function findDesactivated($user) {
        $sql = $this->createQueryBuilder('n');
        $sql
            ->andWhere('n.active = true')
            ->andWhere('n.user = :user')
            ->innerJoin('n.employee', 'e')
            ->andWhere('e.active = false')
            ->setParameter('user', $user);

        $query = $sql->getQuery();

        return $query->getResult();
    }

    public function finOneById($user, $id) {
        $sql = $this->createQueryBuilder('n');
        $sql
            ->andWhere('n.user = :user')
            ->andWhere('n.id = :id')
            ->setParameter('id', $id)
            ->setParameter('user', $user);

        $query = $sql->getQuery();

        return $query->getOneOrNullResult();
    }
}

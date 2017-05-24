<?php

namespace LoginBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findAll()
    {
        return parent::findAll();
    }

    public function findById($id) {
        $sql = $this->createQueryBuilder('u');
        $sql
            ->andWhere('u.id = :id')
            ->setParameter('id', $id);

        $query = $sql->getQuery();

        return $query->getOneOrNullResult();
    }

    public function findBayEmail($email) {
        $sql = $this->createQueryBuilder('u');
        $sql
            ->andWhere('u.email = :email')
            ->setParameter('email', $email);

        $query = $sql->getQuery();

        return $query->getOneOrNullResult();
    }

}

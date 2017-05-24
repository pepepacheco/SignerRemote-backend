<?php

namespace LoginBundle\Repository;

use Doctrine\ORM\EntityRepository;


class RoleRepository extends EntityRepository
{

    public function findAll()
    {
        return parent::findAll();
    }

    public function findOneById($id)
    {
        $sql = $this->createQueryBuilder('r');
        $sql
            ->andWhere('r.id = :id')
            ->setParameter('id', $id);

        $query = $sql->getQuery();

        return $query->getOneOrNullResult();
    }
}

<?php

namespace LoginBundle\Model;

use Doctrine\ORM\EntityManager;
use LoginBundle\Entity\Role;

class RoleModel
{
    const ROLE_ADMIN = "ROLE_ADMIN";
    const ROLE_USER = "ROLE_USER";

    private $repository;
    private $entityManager;

    function __construct(EntityManager $entityManager)
    {
        $this->repository = $entityManager->getRepository("LoginBundle:Role");
        $this->entityManager = $entityManager;
    }

    public function add(Role $user){
        $this->entityManager->persist($user);
    }

    public function update(Role $user){
        $this->entityManager->persist($user);
    }

    public function findById($id){
        return $this->repository->findById($id);
    }

    public function findAll()
    {
        return $this->repository->findAll();
    }

    public function applyChanges(){
        $this->entityManager->flush();
    }

}
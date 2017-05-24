<?php

namespace LoginBundle\Model;

use Doctrine\ORM\EntityManager;

class UserModel
{
    private $entityManager;
    private $repository;

    function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository('LoginBundle:User');
    }

    public function findAll() {
        return $this->repository->findAll();
    }

    public function findById($id) {
        return $this->repository->findById($id);
    }

    public function findByEmail($email) {
        return $this->repository->findBayEmail($email);
    }

    public function add($new) {
        $this->entityManager->persist($new);
    }

    public function applyChanges() {
        $this->entityManager->flush();
    }
}
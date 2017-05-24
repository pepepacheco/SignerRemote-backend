<?php
namespace CoreBundle\Model;

use CoreBundle\Entity\Employee;
use Doctrine\ORM\EntityManager;

class EmployeeModel
{
    private $entityManager;
    private $repository;

    function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository('CoreBundle:Employee');
    }

    public function findAllByUser($user) {
        return $this->repository->findAllByUser($user);
    }

    public function findOneByLogin($email,$password)
    {
        return $this->repository->findOneByLogin($email, $password);
    }

    public function findActivated($user) {
        return $this->repository->findActivated($user);
    }

    public function findDesactivated($user) {
        return $this->repository->findDesactivated($user);
    }

    public function findOneById($user, $id) {
        return $this->repository->findById($user, $id);
    }

    public function findByEmailAndUser($user, $email) {
        return $this->repository->findByEmailAndUser($user, $email);
    }

    public function findByEmail($email) {
        return $this->repository->findByEmail($email);
    }

    public function findByEmailAndPassword($email, $password) {
        return $this->repository->findByEmailAndPassword($email, $password);
    }

    public function add(Employee $new) {
        $this->entityManager->persist($new);
    }

    public function update(Employee $employee) {
        $this->entityManager->merge($employee);
    }

    public function delete(Employee $employee) {
        $this->entityManager->remove($employee);
    }

    public function applyChanges() {
        $this->entityManager->flush();
    }
}
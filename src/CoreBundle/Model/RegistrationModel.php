<?php

namespace CoreBundle\Model;


use CoreBundle\Entity\Employee;
use CoreBundle\Entity\Registration;
use Doctrine\ORM\EntityManager;
use LoginBundle\Entity\User;

class RegistrationModel
{
    private $entityManager;
    private $repository;

    public function findAll() {
        return $this->repository->findAll();
    }

    public function findActivates(User $user) {
        return $this->repository->findActivates($user);
    }

    function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository('CoreBundle:Registration');
    }

    public function add($new) {
        $this->entityManager->persist($new);
    }

    public function update(Registration $registration) {
        $this->entityManager->merge($registration);
    }

    public function applyChanges() {
        $this->entityManager->flush();
    }

    public function calculateIsValidRegistration($longitude1, $longitude2, $latitude1, $latitude2, $radio) {

        if ($longitude1 == 0 and $longitude2 == 0)
            return false;

        return ( pow($longitude1, 2) - pow($longitude2, 2) ) + ( pow($latitude1, 2) - pow($latitude2, 2) ) < pow($radio, 2);
    }
}
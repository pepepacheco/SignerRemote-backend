<?php

namespace CoreBundle\Model;


use CoreBundle\Entity\Registration;
use Doctrine\ORM\EntityManager;

class RegistrationModel
{
    private $entityManager;
    private $repository;

    function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository('CoreBundle:Registration');
    }

    public function add(Registration $new) {
        $this->entityManager->persist($new);
    }

    public function update(Registration $registration) {
        $this->entityManager->merge($registration);
    }

    public function applyChanges() {
        $this->entityManager->flush();
    }

    public function calculateIsValidRegistration($longitude1, $longitude2, $latitude1, $latitude2, $radio) {

        return ( pow($longitude1, 2) - pow($longitude2, 2) ) + ( pow($latitude1, 2) - pow($latitude2, 2) ) < pow($radio, 2);


    }
}
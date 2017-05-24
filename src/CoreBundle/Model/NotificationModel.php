<?php

namespace CoreBundle\Model;

use CoreBundle\Entity\Notification;
use Doctrine\ORM\EntityManager;

class NotificationModel
{
    private $entityManager;
    private $repository;

    function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository('CoreBundle:Notification');
    }

    public function findActivated($user) {
        return $this->repository->findActivated($user);
    }

    public function findActivatedByEmployeeId($employee) {
        return $this->repository->findActivatedByEmployeeId($employee);
    }

    public function findDesactivated($user) {
        return $this->repository->findDesactivated($user);
    }

    public function findOneById($user, $id) {
        return $this->repository->finOneById($user, $id);
    }

    public function save(Notification $new) {
        $this->entityManager->persist($new);
    }

    public function delete(Notification $notification) {
        $this->entityManager->remove($notification);
    }

    public function applyChanges() {
        $this->entityManager->flush();
    }

    public function parseDaysToString($days) {
        $daysRepeatString = '';

        for ($i = 0; $i < sizeof($days); $i++) {

            if (sizeof($days) == 1 || $i == sizeof($days) -2) {
                $daysRepeatString .= $days[$i]->getDescription() . ' ';
            } else if ($i == sizeof($days) -1) {
                $daysRepeatString .= 'y ' . $days[$i]->getDescription() . ' ';
            } else {
                $daysRepeatString .= $days[$i]->getDescription() . ', ';
            }
        }

        return $daysRepeatString;
    }
}
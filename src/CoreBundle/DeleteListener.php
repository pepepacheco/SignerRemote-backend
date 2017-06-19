<?php

namespace CoreBundle;

use CoreBundle\Entity\Notification;
use CoreBundle\Entity\OldNotification;
use CoreBundle\Entity\OldRegistration;
use CoreBundle\Entity\Registration;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\Container;

class DeleteListener
{
    private $container;

    function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function preRemove(LifecycleEventArgs $eventArgs) {

        $entity = $eventArgs->getEntity();

        if ($entity instanceof Notification) {
            $oldNotification = new OldNotification();

            $this->bindDataNotification($entity, $oldNotification);

            $notificationModel = $this->container->get('notification.model');
            $notificationModel->save($oldNotification);

            $oldRegistration = new OldRegistration();

            $this->bindDataRegistrationAndSave($entity->getRegistration(), $oldRegistration, $oldNotification);

        }

    }

    private function bindDataNotification(Notification $notification, OldNotification $oldNotification) {
        $oldNotification->setActive($notification->isActive());
        $oldNotification->setAddress($notification->getAddress());
        $oldNotification->setDayRepeat($notification->getDayRepeat());
        $oldNotification->setEmployee($notification->getEmployee());
        $oldNotification->setLatitude($notification->getLatitude());
        $oldNotification->setLongitude($notification->getLongitude());
        $oldNotification->setRadius($notification->getRadius());
        $oldNotification->setUser($notification->getUser());
        $oldNotification->setSendDate($notification->getSendDate());

    }

    private function bindDataRegistrationAndSave($registration, OldRegistration $oldRegistration, OldNotification $oldNotification) {
        $registrationModel = $this->container->get('registration.model');

        foreach ($registration as $item) {
            $oldRegistration->setSendDate($item->getSendDate());
            $oldRegistration->setLongitude($item->getLongitude());
            $oldRegistration->setLatitude($item->getLatitude());
            $oldRegistration->setEmployee($item->getEmployee());
            $oldRegistration->setIsValid($item->isIsValid());
            $oldRegistration->setNotification($oldNotification);
            $registrationModel->add($item);
        }
        $registrationModel->applyChanges();
    }

}
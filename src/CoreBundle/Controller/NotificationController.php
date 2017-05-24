<?php

namespace CoreBundle\Controller;

use CoreBundle\Entity\Employee;
use CoreBundle\Entity\Notification;
use CoreBundle\Form\NotificationType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use CoreBundle\Form\EmployeeType;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormBuilder;

class NotificationController extends Controller
{

    /**
     * @Route("/web/map", name="load_map")
     */
    public function loadMapAction() {
        return $this->render('@Core/Notify/notify_details.html.twig');
    }

    /**
     * @Route("/web/notification", name="load_notification")
     */
    public function addNotifyAction(Request $request) {
        $notificationModel = $this->get('notification.model');
        $user = $this->getUser();

        $notification = new Notification();

        $form = $this->createForm(new NotificationType(), $notification);
        $form->handleRequest($request);
        $notifications = $notificationModel->findActivated($user);

        if ($form->isValid()) {
            try {
                if ($notification->getLatitude() !== null && $notification->getLatitude() !== null) {

                    $notification->setActive(true);
                    $notification->setUser($user);

                    $notificationModel->save($notification);
                    $notificationModel->applyChanges();

                    $message = 'Notificación creada correctamente';
                } else {
                    $message = 'Error: debe seleccionar una localización';
                }

                $notifications = $notificationModel->findActivated($user);

                return $this->render('@Core/Notify/notify_details.html.twig',
                    array(
                        'notifications' => $notifications,
                        'form' => $form->createView(),
                        'message' => $message
                    )
                );
            } catch (\Exception $exception) {
                return $this->render('@Core/Notify/notify_details.html.twig',
                    array(
                        'notifications' => $notifications,
                        'form' => $form->createView(),
                        'message' => 'Error en el servidor'
                    )
                );
            }
        }

        return $this->render('@Core/Notify/notify_details.html.twig',
            array(
                'form' => $form->createView(),
                'notifications' => $notifications
            )
        );
    }

    /**
     * @Route("/web/notification/delete/.json", options={"expose"=true}, name="notification_delete")
     * @Method("DELETE")
     */
    public function deleteEmployeeAction(Request $request) {
        $notificationsId = $request->get('id');
        $notificationeModel = $this->get('notification.model');
        $user = $this->getUser();

        $deletedIds = array();

        foreach ($notificationsId as $id) {
            $notification = $notificationeModel->findOneById($user, $id);

            if ($notification) {
                $notificationeModel->delete($notification);
                $notificationeModel->applyChanges();

                array_push($deletedIds, $id);
            }
        }
        return new JsonResponse($deletedIds, Response::HTTP_OK);
    }
}
<?php

namespace ApiBundle\Controller;

use CoreBundle\Entity\Notification;
use CoreBundle\Entity\Registration;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

/**
 * @Route("/rest")
 */

class UserController extends FOSRestController
{
    /**
     * @Get("/login/{email}/{password}")
     */
    public function loginAction($email, $password) {
        $view = View::create();
        try {
            $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
            $serializationContext = SerializationContext::create()->enableMaxDepthChecks();
            $serializationContext->setGroups(array('employee'));

            $employeeModel = $this->get('employe.model');

            $employee = $employeeModel->findByEmail($email);

            $password = $encoder->encodePassword($password, $employee->getSalt());

            $employee = $employeeModel->findByEmailAndPassword($email, $password);

            if ($employee) {
                $view
                    ->setData($employee)
                    ->setStatusCode(Codes::HTTP_OK)
                    ->setSerializationContext($serializationContext);
            } else {
                $view
                    ->setStatusCode(Codes::HTTP_BAD_REQUEST)
                    ->setSerializationContext($serializationContext);
            }

        } catch (\Exception $exception) {
            new JsonResponse('error', $exception->getMessage());
        }

        return $view;
    }

    /**
     * @Get("/changePassword/{employeeId}/{userId}/{old}/{new}")
     */
    public function changePasswordAction($employeeId, $userId, $old, $new) {

        $view = View::create();

        try {
            $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);

            $userModel = $this->get('login.user.model');
            $user = $userModel->findById($userId);

            $employeeModel = $this->get('employe.model');
            $employee = $employeeModel->findOneById($user, $employeeId);

            $oldPassword = $encoder->encodePassword($old, $employee->getSalt());

            if (hash_equals($oldPassword, $employee->getPassword())) {
                $newPassword = $encoder->encodePassword($new, $employee->getSalt());

                $employee->setPassword($newPassword);
                $employee->setChangePassword(true);

                $employeeModel->update($employee);
                $employeeModel->applyChanges();

                $view->setStatusCode(Codes::HTTP_NO_CONTENT);

            } else {

                $view->setStatusCode(Codes::HTTP_BAD_REQUEST);
            }

        } catch (\Exception $exception) {
            $view
                ->setData($exception->getTraceAsString())
                ->setStatusCode(Codes::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $view;
    }

    /**
     * @Get("/notifications/{userId}/{employeeId}")
     */
    public function loadNotification($userId, $employeeId) {
        $view = View::create();

        $serializationContext = SerializationContext::create()->enableMaxDepthChecks();
        $serializationContext->setGroups(array('notification'));

        $notificationModel = $this->get('notification.model');
        $userModel = $this->get('login.user.model');
        $employeModel = $this->get('employe.model');

        $user = $userModel->findById($userId);
        $employee = $employeModel->findOneById($user, $employeeId);

        $notifications = $notificationModel->findActivatedByEmployeeId($employee);

        if(sizeof($notifications) > 0) {
            $view
                ->setData($notifications)
                ->setStatusCode(Codes::HTTP_OK)
                ->setSerializationContext($serializationContext);
        } else {
            $view
                ->setStatusCode(Codes::HTTP_BAD_REQUEST);
        }
        return $view;
    }


    /**
     * @Post("/registration/add")
     */
    public function addRegistration(Request $request) {

        $employeeModel = $this->get('employe.model');
        $notificationModel = $this->get('notification.model');
        $registrationModel = $this->get('registration.model');

        //Body params
        $userId = $request->get('userId');
        $employeeId = $request->get('employeeId');
        $notificationId = $request->get('notificationId');
        $latitude = $request->get('latitude');
        $longitude = $request->get('longitude');

        $employee = $employeeModel->findOneById($userId, $employeeId);
        $notification = $notificationModel->findOneById($userId, $notificationId);
        $isValid = $registrationModel->calculateIsValidRegistration(
            $longitude,
            $notification->getLongitude(),
            $latitude,
            $notification->getLatitude(),
            $notification->getRadius()
        );

        $sendDate = new \DateTime();

        $registration = new Registration();

        $registration->setEmployee($employee);
        $registration->setNotification($notification);
        $registration->setLatitude($longitude);
        $registration->setLongitude($latitude);
        $registration->setSendDate($sendDate);
        $registration->setIsValid($isValid);

        $registrationModel->add($registration);
        $registrationModel->applyChanges();

        $view = View::create();
        $view->setData($isValid);
        $view->setStatusCode(Codes::HTTP_CREATED);

        return $view;

    }
}

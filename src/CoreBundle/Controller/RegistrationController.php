<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class RegistrationController extends Controller
{

    /**
     * @Route("/web/registration", name="load_registration")
     */
    public function listAction() {

        $user = $this->getUser();

        $registrationModel = $this->get('registration.model');

        $registrations = $registrationModel->findActivates($user);

        return $this->render('@Core/Registration/registration_list.html.twig', array(
            'registrations' => $registrations
        ));

    }

}
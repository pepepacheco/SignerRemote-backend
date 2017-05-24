<?php

namespace LoginBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    /**
     * @Route("/", name="login")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUserName = $authenticationUtils->getLastUsername();

        $email = null;
        if ($request->get('email'))
            $email = $request->get('email');

        return $this->render('LoginBundle::base.html.twig',
            array(
                'email' => $email,
                'last_username' => $lastUserName,
                'err' => $error,
            )
        );
    }
}
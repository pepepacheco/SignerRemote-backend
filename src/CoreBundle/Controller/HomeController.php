<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class HomeController extends Controller
{
    /**
     * @Route("/web/home", name="home")
     */
    public function loadHomeAction() {

        $userName = $this->getUser()->getName();

        return $this->render('@Core/base_dashboard.html.twig', array('user_name' => $userName));
    }
}
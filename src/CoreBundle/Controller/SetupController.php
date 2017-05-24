<?php

namespace CoreBundle\Controller;

use LoginBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class SetupController extends Controller
{
    /**
     * @Route("/web/employess/disabled", name="employess_disabled")
     */
    public function showDisabledEmployeesAction() {

        $user = $this->getUser();
        $employeeModel = $this->get('employe.model');

        $employess = $employeeModel->findDesactivated($user);

        return $this->render('@Core/Employee/disabled_employe_list.html.twig', array('employees' => $employess));
    }

    /**
     * @Route("/web/employees/activate", options={"expose"=true}, name="activate_employee")
     * @Method("PUT")
     */
    public function activateEmployeeAction(Request $request) {
        $user = $this->getUser();
        $employeeId = $request->get('id');

        $employeModel = $this->get('employe.model');

        $employee = $employeModel->findOneById($user, $employeeId);

        $employee->setActive(true);

        $employeModel->update($employee);
        $employeModel->applyChanges();

        return new JsonResponse('ok', 200);

    }

    /**
     * @Route("/web/user/edit", options={"expose"=true}, name="edit_user")
     */
    public function editUserAction(Request $request) {
        $user = $this->getUser();

        $form = $this->createForm(new UserType(), $user);
        $form->handleRequest($request);

        if ($form->isValid()) {

        }

        return $this->render('@Login/user/form_user_edit.html.twig', array('form_user' => $form->createView()));
    }

}
<?php
namespace CoreBundle\Controller;

use CoreBundle\Entity\Employee;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use CoreBundle\Form\EmployeeType;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class EmployeeController extends Controller
{

    /**
     * @Route("/web/employee", name="employee_list")
     * @Method("GET")
     */
    public function listAction() {
        $user = $this->getUser();
        $employeeModel = $this->get('employe.model');

        $employees = $employeeModel->findActivated($user);

        return $this->render('@Core/Employee/employee_list_detail.html.twig',
            array('employees' => $employees)
        );
    }

    /**
     * @Route("/web/employee/new", name="employee_add")
     */
    public function addEmployee(Request $request) {
        $employee = new Employee();
        $form = $this->createForm(new EmployeeType(), $employee);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $this->getUser();
            $employeeModel = $this->get('employe.model');

            $employee->setChangePassword(false);
            $employee->setUser($user);
            $employee->setActive(true);
            $employee->setSalt(md5(time()));

            $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
            $password = $encoder->encodePassword($employee->getPassword(), $employee->getSalt());

            $employee->setPassword($password);

            $employeeModel->add($employee);
            $employeeModel->applyChanges();

            return $this->render('@Core/Employee/employee_add.html.twig',
                array('form' => $form->createView(), 'message' => 'Empleado añadido correctamente')
            );
        }

        return $this->render('@Core/Employee/employee_add.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/web/employee/show/edit", options={"expose"=true}, name="employee_show_edit")
     * @Method("GET")
     */
    public function showEditEmployee(Request $request) {
        $employeeId = $request->get('id');
        $employeModel = $this->get('employe.model');
        $user = $this->getUser();

        $employee = $employeModel->findOneById($user, $employeeId);

        $form = $this->createForm(new EmployeeType(), $employee);

        return new JsonResponse(array(
            'form' => $this->renderView('@Core/Employee/_employee_form_edit.html.twig',
                array(
                    'employee' => $employee,
                    'form' => $form->createView()
                ))
        ), 200);
    }

    /**
     * @Route("/web/employee/edit", options={"expose"=true}, name="employee_edit")
     * @Method("POST")
     */
    public function editEmployee(Request $request) {
        $formData = $request->get('corebundle_employee');
        $employeModel = $this->get('employe.model');
        $user = $this->getUser();

        $idEmployee = $formData['id'];
        $employee = $employeModel->findOneById($user, $idEmployee);
        $employee = $this->bindData($employee, $formData);

        $employeModel->update($employee);
        $employeModel->applyChanges();

        return new Response('ok', 200);
    }

    /**
     * @Route("/web/employee/delete/.json", options={"expose"=true}, name="employee_delete")
     * @Method("DELETE")
     */
    public function deleteEmployeeAction(Request $request) {
        $employeeId = $request->get('id');
        $employeModel = $this->get('employe.model');
        $user = $this->getUser();

        $deletedIds = array();

        foreach ($employeeId as $id) {
            $employee = $employeModel->findOneById($user, $id);

            if ($employee) {
                $employee->setActive(false);
                $employeModel->applyChanges();

                array_push($deletedIds, $id);
            }
        }

        return new JsonResponse($deletedIds, Response::HTTP_OK);
    }

    private function bindData(Employee $employee, $formData) {
        $name = $formData['name'];
        $lastName = $formData['lastName'];
        $email = $formData['email'];
        $phone = $formData['phone'];
        $birthdate = $formData['birthdate'];
        $password = $formData['password'];
        $workstation = $formData['workstation'];
        if ($formData['woman'])
            $isWoman = $formData['woman'];

        if ($name) {
            $employee->setName($name);
        }
        if ($lastName) {
            $employee->setLastName($lastName);
        }
        if ($email) {
            $employee->setEmail($email);
        }
        if ($phone) {
            $employee->setPhone($phone);
        }
        if ($birthdate) {
            $date = new \DateTime($formData['birthdate']);
            if ($date) {
                $employee->setBirthdate($date);
            }
        }
        if ($password) {
            $encoders = $this->container->get('security.password_encoder');

            $password = $encoders->encodePassword($employee, $password);
            $employee->setPassword($password);
        }
        if ($workstation) {
            $employee->setWorkstation($workstation);
        }

        if (isset($isWoman)) {
            if ($isWoman == "1")
                $employee->setWoman(1);

            else if ($isWoman == "0")
                $employee->setWoman(0);
        }

        return $employee;
    }

}
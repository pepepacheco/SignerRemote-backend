<?php

namespace LoginBundle\Controller;

use LoginBundle\Entity\Role;
use LoginBundle\Entity\User;
use LoginBundle\Form\UserType;
use LoginBundle\Model\RoleModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{

    /**
     * @Route("/user/register", name="new_user")
     */
    public function newUser(Request $request) {

        $formUser = $this->createForm(new UserType());
        $formUser->handleRequest($request);

        if($formUser->isSubmitted()) {
            $userModel = $this->get('login.user.model');

            $formData = $request->get('loginbundle_user');
            $email = $formData['email'];

            $isEmail = $userModel->findByEmail($email);

            if ($isEmail !== null) {
                return $this->render('@Login/user/form_user.html.twig', array(
                    'form_user' => $formUser->createView(), 'err' => 'usuario ya registrado'
                ));

            } else {

                //USER
                $user = new User();

                $user->setName($formData['name']);
                $user->setLastname($formData['lastname']);
                $user->setEmail($email);

                $password = $formData['password']['first'];
                $encoders = $this->container->get('security.password_encoder');

                $password = $encoders->encodePassword($user, $password);

                $user->setPassword($password);

                $date = new \DateTime($formData['birthdate']);
                $user->setBirthdate($date);

                $userModel->add($user);
                $userModel->applyChanges();

                //ROLE
                $roleModel = $this->get('login.role.model');

                $role = new Role();
                $role->setName(RoleModel::ROLE_USER);
                $role->setUser($user);

                $roleModel->add($role);
                $roleModel->applyChanges();

                return $this->redirect($this->generateUrl('login', array('email' => $user->getEmail())));
            }
        }

        return $this->render('@Login/user/form_user.html.twig', array(
           'form_user' => $formUser->createView(), 'err' => null
        ));

    }
}
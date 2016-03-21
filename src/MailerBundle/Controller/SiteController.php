<?php

namespace MailerBundle\Controller;

use MailerBundle\Entity\User;
use MailerBundle\Form\UserType;
use MailerBundle\Entity\UserRole;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SiteController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('MailerBundle:Site:index.html.twig');
    }


    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'MailerBundle:Site:login.html.twig', [
                'last_username' => $lastUsername,
                'error'         => $error,
            ]
        );

    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function signupAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(new UserType(), $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $role = $this->getDoctrine()
                         ->getRepository('MailerBundle:Role')
                         ->find(1);
            $userRole = new UserRole();
            var_dump($role); exit;
            $userRole->setRoleId($role->getId());
            $user->addUserRole($userRole);

            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $userRole->setUserId($user->getId());

            $em->persist($userRole);
            $em->flush();

            return $this->redirectToRoute('email');
        }

        return $this->render(
            'MailerBundle:Site:signup.html.twig', [
                'form' => $form->createView()
            ]
        );
    }
}
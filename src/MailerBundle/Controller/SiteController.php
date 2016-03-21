<?php

namespace MailerBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use MailerBundle\Entity\Role;
use MailerBundle\Entity\User;
use MailerBundle\Form\UserType;
use MailerBundle\Entity\UserRole;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

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
            /*$role = $this->getDoctrine()
                         ->getRepository('MailerBundle:Role')
                         ->find(1);
            $user->setUserRoles(new ArrayCollection([$role]));

            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();*/
            $role = new Role();
            $role->setName('ROLE_USER');

            $em = $this->getDoctrine()->getManager();

             $em->persist($role);

            $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
            $password = $encoder->encodePassword('admin', md5(time()));
            $user->setPassword($password);

            $user->getUserRoles()->add($role);
            $em->persist($user);

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
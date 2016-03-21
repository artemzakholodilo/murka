<?php

namespace MailerBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use MailerBundle\Entity\Role;
use MailerBundle\Entity\User;
use MailerBundle\Form\UserType;
use MailerBundle\Entity\UserRole;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
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

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
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
            $role = new Role();
            $role->setName('ROLE_USER');

            $em = $this->getDoctrine()->getManager();

             $em->persist($role);

            $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
            $password = $encoder->encodePassword($user->getPassword(), md5(time()));
            $user->setPassword($password);

            $user->getUserRoles()->add($role);
            $em->persist($user);
            $em->flush();

            $this->setToken($user, $request);

            return $this->redirectToRoute('email');
        }

        return $this->render(
            'MailerBundle:Site:signup.html.twig', [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @param User $user
     * @param Request $request
     */
    private function setToken(User $user, Request $request)
    {
        $token = new UsernamePasswordToken(
            $user->getUsername(),
            $user->getPassword(),
            'main',
            $user->getRoles()
        );
        $this->get('security.context')->setToken($token);
        $request->getSession()->set('_security_secured_area', serialize($token));

    }
}
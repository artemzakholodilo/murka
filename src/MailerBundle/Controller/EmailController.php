<?php

namespace MailerBundle\Controller;

use MailerBundle\Entity\EmailHandler\EmailSender;
use MailerBundle\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class EmailController extends Controller
{
    /**
     * @var string email address from what mail be send
     */
    private $transportEmail;

    /**
     * EmailController constructor.
     * @param ContainerInterface $container
     * @param $transportEmail
     */
    public function __construct(
        ContainerInterface $container,
        $transportEmail
    )
    {
        $this->setContainer($container);
        $this->transportEmail = $transportEmail;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('MailerBundle:Email:index.html.twig');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')
                     ->getToken()->getUser();
        $notification = $this->getNotification($request, $user);
        $notification->setNotificationBodyData();

        $validator = $this->get('validator');
        $errors = $validator->validate($notification);

        count($errors) == 0 ? : $this->render(
            'MailerBundle:Email:index.html.twig',
            ['errors' => $errors]
        );

        $data = $notification->toJson($this->transportEmail);

        try {
            $amqpSender = new EmailSender();
            $amqpSender->send($data);
        } catch (\Exception $ex) {
            return $this->render('MailerBundle:Email:index.html.twig', [
                'errors' => [
                    'Error something went wrong. Please try agin later'
                ]
            ]);
        }

        return $this->render('MailerBundle:Email:success.html.twig');
    }

    /**
     * @param Request $request
     * @param $user
     * @return Notification
     */
    private function getNotification(Request $request, $user)
    {
        $notification = new Notification();
        $notification->setBody($request->request->get('body'));
        $notification->setFrom($user);
        $notification->setSubject($request->request->get('subject'));
        $notification->setTo($request->request->get('to'));

        return $notification;
    }
}

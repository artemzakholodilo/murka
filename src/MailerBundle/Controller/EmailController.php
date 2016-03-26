<?php

namespace MailerBundle\Controller;

use MailerBundle\Entity\EmailHandler\EmailReceiver;
use MailerBundle\Entity\EmailHandler\EmailSender;
use MailerBundle\Sender\AbstractSender;
use MailerBundle\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class EmailController extends Controller
{
    /**
     * @var AbstractSender
     */
    private $sender;

    /**
     * EmailController constructor.
     * @param AbstractSender $sender
     * @param ContainerInterface $container
     */
    public function __construct
    (
        AbstractSender $sender,
        ContainerInterface $container
    )
    {
        $this->sender = $sender;
        $this->container = $container;
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
        $notification = new Notification();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $notification->setBody($request->request->get('subject'), $user);
        $notification->setSubject($request->request->get('body'));
        $notification->setTo($request->request->get('to'));

        $data = $notification->toJson($this->sender->getMail());

        try {
            $amqpSender = new EmailSender();
            $message = $amqpSender->send($data);

            $receiver = new EmailReceiver($this->sender);
            // echo "<pre>";
            // print_r($message); exit;
            $receiver->receive($message);
        } catch (\Exception $ex) {
            return $this->render('MailerBundle:Email:error.html.twig', [
                'error' => $ex->getMessage()
            ]);
        }

        return $this->render('MailerBundle:Email:success.html.twig');
    }
}

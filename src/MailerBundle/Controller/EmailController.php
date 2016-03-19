<?php

namespace MailerBundle\Controller;

use MailerBundle\Entity\EmailHandler\EmailReceiver;
use MailerBundle\Entity\EmailHandler\EmailSender;
use MailerBundle\Sender\EmailSender as Sender;
use MailerBundle\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EmailController extends Controller
{
    /**
     * @var Sender
     */
    private $sender;

    /**
     * EmailController constructor.
     * @param Sender $sender
     */
    public function __construct(Sender $sender)
    {
        $this->sender = $sender;
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
        $notification->setBody($request->request->get('subject'), $user->getUsername());
        $notification->setSubject($request->request->get('body'));

        $amqpSender = new EmailSender();
        $message = $amqpSender->send($notification->toJson());

        $receiver = new EmailReceiver($this->sender);
        $receiver->receive($message);

        try {
            $this->sender->send($notification);
        } catch (\Exception $ex) {
            $this->render('MailerBundle:Email:error.html.twig', [
                'message' => $ex->getMessage()
            ]);
        }

        $this->render('MailerBundle:Email:success.html.twig');
    }
}

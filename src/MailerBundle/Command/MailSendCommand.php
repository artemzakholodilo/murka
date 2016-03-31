<?php

namespace MailerBundle\Command;

use MailerBundle\Entity\EmailHandler\EmailReceiver;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class MailSendCommand
 * @package MailerBundle\Command
 */
class MailSendCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mail:send')
            ->setDescription('Rabbit send message')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $receiver = new EmailReceiver(
            $this->getContainer()->get('mailer.emailsender')
        );
        $output->writeln("Worker sending messages.");

        $receiver->listen();
    }
}
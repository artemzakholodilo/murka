<?php

namespace MailerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MailerExtension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @param array $configs An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $container->setDefinition('mailer.emailsender', new Definition('MailerBundle\Sender\EmailSender',[
            new Reference('swiftmailer.mailer'),
            $container->getParameter('mailer_user'),
            $container->getParameter('mailer_password')
        ]));

        $container->setDefinition('mailer.email_controller', new Definition('MailerBundle\Controller\EmailController',[
            new Reference('mailer.emailsender'),
            new Reference('service_container')
        ]));
    }
}
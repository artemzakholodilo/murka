<?php

namespace MailerBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use MailerBundle\Entity\Role;
use MailerBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class FixtureLoader implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $role = new Role();
        $role->setName('ROLE_ADMIN');

        $manager->persist($role);

        $user = new User();
        $user->setName('john.doe@example.com');

        $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
        $password = $encoder->encodePassword('admin', md5(time()));
        $user->setPassword($password);

        $user->getUserRoles()->add($role);

        $manager->persist($user);

        $manager->flush();
    }
}
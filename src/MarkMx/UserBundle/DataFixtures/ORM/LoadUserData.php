<?php

namespace MarkMx\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;
use MarkMx\UserBundle\Entity\User;

class LoadUserData extends ContainerAware implements FixtureInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('fos_user.user_manager');

        // Create super admin
        $user = $userManager->createUser();
        $user->setUsername('Adam')
             ->setEmail('adam@example.com')
             ->setPlainPassword('zaq1xsw2')
             ->setSuperAdmin(true)
             ->setEnabled(true);
        $userManager->updateUser($user);

        // Create admins
        $user = $userManager->createUser();
        $user->setUsername('Barbara')
             ->setEmail('barbara@acme.com')
             ->setPlainPassword('1qaz2wsx')
             ->setRoles(array('ROLE_ADMIN'))
             ->setEnabled(true);
        $userManager->updateUser($user);

        // Create standard users
        $user = $userManager->createUser();
        $user->setUsername('Charlie')
             ->setEmail('charlie@example.org')
             ->setPlainPassword('xsw2zaq1')
             ->setEnabled(true);
        $userManager->updateUser($user);

        $user = $userManager->createUser();
        $user->setUsername('Delia')
             ->setEmail('delia@example.org')
             ->setPlainPassword('cde32wsx')
             ->setEnabled(true);
        $userManager->updateUser($user);

        $user = $userManager->createUser();
        $user->setUsername('Ernie')
             ->setEmail('ernie@example.org')
             ->setPlainPassword('cde31qaz')
             ->setEnabled(true);
        $userManager->updateUser($user);
    }
}

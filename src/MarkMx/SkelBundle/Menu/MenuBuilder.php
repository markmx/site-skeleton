<?php
namespace MarkMx\SkelBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\Iterator\RecursiveItemIterator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MenuBuilder
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function createMainMenu(Request $request, FactoryInterface $factory)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('About', array('route' => 'about'));

        if ($this->isLoggedIn()) {
            $accountMenu = $menu->addChild('My account');
            $accountMenu->addChild('View profile', array('route' => 'fos_user_profile_show'));
            $accountMenu->addChild('Edit profile', array('route' => 'fos_user_profile_edit'));
            $accountMenu->addChild('Change password', array('route' => 'fos_user_change_password'));

            if ($this->isGranted('ROLE_ADMIN')) {
                $adminMenu = $menu->addChild('Admin');
                $adminMenu->addChild('Users', array('route' => 'user_admin'));
            }

            $menu->addChild('Sign out ' . $this->getUsername(), array('route' => 'fos_user_security_logout'));
        } else {
            $menu->addChild('Sign up', array('route' => 'fos_user_registration_register'));
            $menu->addChild('Sign in', array('route' => 'fos_user_security_login'));
            $menu->addChild('Reset password', array('route' => 'fos_user_resetting_request'));
        }

        $itemIterator = new RecursiveItemIterator($menu);
        $iterator = new \RecursiveIteratorIterator($itemIterator, \RecursiveIteratorIterator::SELF_FIRST);

        $currentUri = $request->getUri();
        
        foreach ($iterator as $item) {
            if ($item->getUri() == $currentUri) {
                $item->setCurrent(true);
                break;
            }
        }

        return $menu;
    }

    private function isLoggedIn()
    {
        return $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY');
    }

    private function getUsername()
    {
        return $this->container->get('security.context')->getToken()->getUser();
    }

    private function isGranted($role)
    {
        return $this->container->get('security.context')->isGranted($role);
    }

    private function getRouter()
    {
        return $this->container->get('router');
    }
}

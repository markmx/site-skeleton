<?php

namespace MarkMx\SkelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use MarkMx\SkelBundle\Form\ContactType;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="_default")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/hello/{name}", name="_default_hello")
     * @Template()
     */
    public function helloAction($name)
    {
        return array('name' => $name);
    }

    /**
     * @Route("/email", name="_email")
     * @Template()
     */
    public function emailAction()
    {
        $name = $this->container->get('security.context')->getToken()->getUser();

        if ($name != 'Mark') {
            throw new \Exception('Not authorized');
        } else {
            $message = \Swift_Message::newInstance()
                ->setSubject('Test Hello Email')
                ->setTo('mark.maynereid@gmail.com')
                ->setBody(
                    $this->renderView(
                        'MarkMxSkelBundle:Default:email.txt.twig',
                        array('name' => $name)
                    )
                )
            ;
            $this->get('mailer')->send($message);

            return $this->render('MarkMxSkelBundle:Default:email-confirmed.html.twig');
        }
    }
}

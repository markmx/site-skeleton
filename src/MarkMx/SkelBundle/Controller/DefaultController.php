<?php

namespace MarkMx\SkelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader as DataFixturesLoader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use MarkMx\SkelBundle\Form\Type\ResetDbType;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MarkMxSkelBundle:Default:index.html.twig');
    }

    public function aboutAction()
    {
        return $this->render('MarkMxSkelBundle:Default:about.html.twig');
    }

    public function resetDbAction(Request $request)
    {
        $form = $this->createForm(new ResetDbType);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->resetDb();
            $this->get('session')->getFlashBag()->add('notice', 'Demo DB reset!');

            return $this->redirect($this->generateUrl('homepage'));
        }

        return $this->render(
            'MarkMxSkelBundle:Default:reset-db.html.twig',
            array('form' => $form->createView())
        );
    }

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

    protected function resetDb()
    {
        $doctrine = $this->get('doctrine');
        $em = $doctrine->getManager();

        $paths = array();

        foreach ($this->get('kernel')->getBundles() as $bundle) {
            $paths[] = $bundle->getPath().'/DataFixtures/ORM';
        }

        $loader = new DataFixturesLoader($this->container);

        foreach ($paths as $path) {
            if (is_dir($path)) {
                $loader->loadFromDirectory($path);
            }
        }

        $fixtures = $loader->getFixtures();

        if (!$fixtures) {
            throw $this->createNotFoundException('Could not find any fixtures to load.');
        }

        $purger = new ORMPurger($em);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);
        $executor = new ORMExecutor($em, $purger);
        $executor->execute($fixtures);
    }
}

<?php
namespace MarkMx\SkelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use MarkMx\SkelBundle\Form\Type\ResetDbType;

use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader as DataFixturesLoader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;

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
     * @Route("/about", name="about")
     * @Template()
     */
    public function aboutAction()
    {
        return array();
    }

    /**
     * @Route("/reset-db", name="reset_db")
     * @Template()
     */
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

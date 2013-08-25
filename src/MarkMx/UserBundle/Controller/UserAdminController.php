<?php
namespace MarkMx\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MarkMx\UserBundle\Form\Type\UserEntitySelectType;
use Symfony\Component\HttpFoundation\Request;

class UserAdminController extends Controller
{
    public function indexAction(Request $request)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $users = $userManager->findUsers();
        $form = $this->createForm(new UserEntitySelectType, $users);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $formData = $form->getData();
            $taskMethod = $formData['task'];
            call_user_func(array($this, $taskMethod), $formData['id']);

            return $this->redirect($this->generateUrl('user_admin'));
        }

        return $this->render(
            'MarkMxUserBundle:UserAdmin:index.html.twig',
            array('form' => $form->createView(), 'user_entity_collection' => $users)
        );
    }

    protected function deactivate($formData)
    {
        $em = $this->getDoctrine()->getManager();

        foreach ($formData as $user) {
            $user->setEnabled(false);
            $em->persist($user);
        }

        $em->flush();
    }

    protected function activate($formData)
    {
        $em = $this->getDoctrine()->getManager();

        foreach ($formData as $user) {
            $user->setEnabled(true);
            $em->persist($user);
        }
        $em->flush();
    }
}

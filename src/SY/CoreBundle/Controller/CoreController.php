<?php

namespace SY\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CoreController extends Controller
{
    public function indexAction()
    {
        $listAdverts = $this->get('sy_core.adverts')->getAdverts();

        return $this->render('SYCoreBundle:Core:index.html.twig', ['listAdverts' => $listAdverts]);
    }


    public function contactAction(Request $request)
    {
        if (!$request->isMethod('POST')) {

            $request->getSession()->getFlashBag()->add('notice', 'La page de contact n’est pas encore disponible.');

            return $this->redirectToRoute('sy_core_homepage');

        }

        return $this->render('@SYCore/Core/contact.html.twig');
    }
}

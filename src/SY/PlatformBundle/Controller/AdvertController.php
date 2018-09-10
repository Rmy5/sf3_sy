<?php

namespace SY\PlatformBundle\Controller;


use SY\PlatformBundle\Entity\Advert;
use SY\PlatformBundle\Form\AdvertEditType;
use SY\PlatformBundle\Form\AdvertType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{
    /**
     * @param $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction( $page )
    {
        $em = $this->getDoctrine()->getManager();

        $nbPerPage = 3;

        $listAdverts =   $em->getRepository('SYPlatformBundle:Advert')->getAdverts( $page, $nbPerPage);

        $pages = ceil(count($listAdverts) / $nbPerPage );

        if ($page > $pages) {
            throw new NotFoundHttpException('Page ' . $page . ' inexistante');
        }


        return $this->render('@SYPlatform/Advert/index.html.twig',
            [
                'listAdverts' => $listAdverts,
                'pages' => $pages,
                'page' => $page
            ]);
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction( $id )
    {

        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('SYPlatformBundle:Advert')->find($id);


        $applications = $em->getRepository('SYPlatformBundle:Application')
                           ->findBy(['advert' => $advert]);

        if ($advert === null) {
            throw new NotFoundHttpException('L\'annonce d\'id ' . $id . ' n\'existe pas');
        }

        $addsSkills = $em->getRepository('SYPlatformBundle:AdvertSkill')
                         ->findBy(['advert' => $advert]);


        return $this->render('@SYPlatform/Advert/view.html.twig',
            [
                'advert' => $advert,
                'applications' => $applications,
                'addsSkills' => $addsSkills
            ]);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function addAction(Request $request)
    {
        $advert = new Advert();

        // préremplir un champ en hydratant l'objet avant de le passer au constructeur
//        $advert->setEmail('test@test.com');

        // Modifier une entité de la db avec un formulaire
//        $advert = $this->getDoctrine()->getRepository('SYPlatformBundle:Advert')->find(26);

//        $form = $this->get('form.factory')->create(AdvertType::class, $advert);

        // Shorthand
        $form = $this->createForm(AdvertType::class, $advert);


        if ( $request->isMethod('POST') && $form->handleRequest($request)->isValid() ) {

            $em = $this->getDoctrine()->getManager();

            $em->persist($advert);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée');

            return $this->redirectToRoute('sy_platform_view', ['id' => $advert->getId()]);
        }

        return $this->render('@SYPlatform/Advert/add.html.twig',
            [
                'form' => $form->createView()
            ]);
    }

    /**
     * @param $id
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('SYPlatformBundle:Advert')->find($id);

        $form = $this->createForm(AdvertEditType::class, $advert);

        if ($advert === null) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . "n'existe pas");
        }

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid() ) {

            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

            return $this->redirectToRoute('sy_platform_view', ['id' => $advert->getId()]);
        }

        return $this->render('@SYPlatform/Advert/edit.html.twig',
            [
                'advert' => $advert,
                'form' => $form->createView()
            ]);
    }

    /**
     * @param $id
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('SYPlatformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas");
        }

        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            foreach ($advert->getCategories() as $category) {
                $advert->removeCategory($category);
            }
            $em->remove($advert);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien supprimée.');

            return $this->redirectToRoute('sy_platform_home');
        }
        return $this->render('SYPlatformBundle:Advert:delete.html.twig',
            [
                'advert' => $advert,
                'form' => $form->createView()
            ]);
    }

    /**
     * @param $limit
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function menuAction($limit)
    {
        $em = $this->getDoctrine()->getManager();

        $listAdverts =   $em->getRepository('SYPlatformBundle:Advert')->getAdverts(1, $limit);

        return $this->render('SYPlatformBundle:Advert:menu.html.twig', ['listAdverts' => $listAdverts]);
    }



    public function testAction()
    {
        $advert = new Advert;

        $advert->setDate(new \DateTime());
        $advert->setTitle('heyhofgddddhgfhfdghhfghfgh');
        $advert->setAuthor('Gfghfg');
        $advert->setContent('bla bla bla');

        $validator = $this->get('validator');

        $listErrors = $validator->validate($advert);

        if (count($listErrors) > 0) {

            return new Response((string) $listErrors);
        } else {
            return new Response("L'annonce est valide");
        }
    }
}

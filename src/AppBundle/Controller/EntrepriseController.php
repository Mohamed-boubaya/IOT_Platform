<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Entreprise;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Ob\HighchartsBundle\Highcharts\Highchart;
use AppBundle\Form\EntrepriseType;

class EntrepriseController extends Controller
{
  /**
     * @Route("/entreprises",  name="entreprise_list")
     * @Template("AppBundle:Entreprise:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        $filter = "all";
        $filterValue = "";
        $order = "entreprise";
        if ($request->query->has('filter')){
            $filter = $request->query->get('filter');
            if ($request->query->has('filterValue')){
                $filterValue = $request->query->get('filterValue');
            }
        }
        if ($request->query->has('order')){
            $order = $request->query->get('order');
        }
        $entreprises = $this->getDoctrine()->getRepository('AppBundle:Entreprise')->findAllEntreprises($filter,$filterValue,$order);
        return array(
            'entreprises' => $entreprises,
        );
    }

    /**
     * @Route("/entreprises/new", name="entreprise_new")
     * @Template("AppBundle:Entreprise:new.html.twig")
     */
    public function newAction(Request $request)
    {
        $entreprise = new Entreprise();
        $form = $this->createForm(new EntrepriseType(), $entreprise);
        $form->add('Add', 'submit');
        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entreprise);
            $em->flush();

            $this->addFlash('success', "entreprise added with success!");
            return $this->redirectToRoute('entreprise_view', ['slug' => $entreprise->getSlug()]);
        }

        return array(
            'form' => $form->createView(),

        );
    }

 
    /**
     * @Route("/entreprises/{slug_entreprise}", name="entreprise_view")
     * @Template("AppBundle:Entreprise:view.html.twig")
     */
    public function viewAction($slug_entreprise, Request $request)
    {

   
        $entreprise = $this->getDoctrine()->getRepository('AppBundle:Entreprise')->findEntrepriseBySlug($slug_entreprise);
      ///  if (!$entreprise->isCondidate())
         //   throw new HttpException("");
   
        return array(
            'entreprise' => $entreprise,
        );
    }

   


   

    /**
     * @Route("/entreprises/{slug}/edit", name="entreprise_edit")
     * @Template("AppBundle:Entreprise:edit.html.twig")
    *@Security("is_granted('ROLE_ADMIN')")
     */
    public function editAction(Entreprise $entreprise, Request $request)
    {
        $form = $this->createForm(new EntrepriseType(), $entreprise);
        $form->add('Edit', 'submit');
        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entreprise);
            $em->flush();

            $this->addFlash('success', "entreprise updated !");
            return $this->redirectToRoute('entreprise_view', ['slug_entreprise' => $entreprise->getSlug()]);
        }
        return array(
            'form' => $form->createView(),
            'entreprise'=> $entreprise
            );
    }

   
 /**
     * @Route("/entreprises/{slug}/delete", name="entreprise_delete")
     *@Template()
     *@Security("is_granted('ROLE_ADMIN')")
     */

    public function deleteAction(Promo $promo)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($promo);
        $em->flush();
        $this->addFlash('success', "promo deleted !");
        return $this->redirectToRoute('promo_list');
    }

}

<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Form\ContactType;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ContactController
 * @package AppBundle\Controller
 * @Route("/contact")
 */
class ContactController extends Controller
{
    /**
     * @Route("/", name="contact")
     * @Template("AppBundle:Contact:new.html.twig")
     */
    public function newAction(Request $request)
    {
        $contact = new Contact();
        $form = $this->createForm(new ContactType(), $contact);
        $form->add('send', 'submit');
        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($contact);
            $em->flush();
            $this->addFlash('success', "Votre mesage a été envoyé avec succès !");
            return $this->redirectToRoute('contact');
        }
        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/all/{filter}", name="contacts")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template("")
     */
    public function indexAction($filter="all")
    {
        if ($filter=="all")
            $contacts = $this->getDoctrine()->getRepository('AppBundle:Contact')->findBy(array(), array('createdAt' => 'DESC'));
        else if ($filter=="new")
            $contacts = $this->getDoctrine()->getRepository('AppBundle:Contact')->findBy(array('viewed'=>0), array('createdAt' => 'DESC'));
        else  $contacts = $this->getDoctrine()->getRepository('AppBundle:Contact')->findBy(array('viewed'=>1), array('createdAt' => 'DESC'));
        return array(
            'contacts' => $contacts,
            'filter' => $filter
        );
    }

    /**
     * @Route("/{id}", name="contact_view")
     * @Template("")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function viewAction(Contact $contact, Request $request)
    {
        return array(
            'contact' => $contact,
        );
    }
}

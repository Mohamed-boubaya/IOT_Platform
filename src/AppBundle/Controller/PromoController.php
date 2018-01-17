<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Promo;
use AppBundle\Entity\Participation;
use AppBundle\Form\PromoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Ob\HighchartsBundle\Highcharts\Highchart;

/**
 * Class PromoController
 * @package AppBundle\Controller
 * @Route("/promo")
 */

class PromoController extends Controller
{
    /**
     * @Route("/", name="promo_list")
se""     * @Template()
     */

    public function indexAction(Request $request)
    {
        $filter = "all";
        $filterValue = "";
        $order = "eleve";
        if ($request->query->has('filter')){
            $filter = $request->query->get('filter');
            if ($request->query->has('filterValue')){
                $filterValue = $request->query->get('filterValue');
            }
        }
        if ($request->query->has('order')){
            $order = $request->query->get('order');
        }
        $promos = $this->getDoctrine()->getRepository('AppBundle:Promo')->findAllPromos($filter, $filterValue, $order);
        return array(
            'promos' => $promos,
        );
    }

    /**
     *  @Route("/new", name="promo_new")
     *  @Template()
     *  @Security("is_granted('ROLE_ADMIN')")
     */

    public function newAction(Request $request)
    {
            $promo = new Promo();
            $form = $this->createForm(new PromoType(), $promo);
            $form->add('Add', 'submit');
            $form->handleRequest($request);

    if($form->isValid()){
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($promo);
            $em->flush();

            $this->addFlash('success', "Promo added with success!");
            return $this->redirectToRoute('promo_view', ['slug' => $promo->getSlug()]);
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/{slug}/contact", name="promo_contact")
     * @Template()
     * @Security("is_granted('ROLE_ADMIN')")
     */

    public function contactAction(Promo $promo,Request $request)
    {
        $emailsA = $this->getDoctrine()->getRepository('AppBundle:Participation')->getParticipantsEmails($promo->getId());
        $emails = "";
        foreach($emailsA as $email){
            $emails.=trim($email['email']).';';
        }
        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('destination','textarea',array('required'=>true,'data'=>$emails,'pattern'=>'[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{1,}(;[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{1,})*;$'))
            ->add('subject','text',array('required'=>true, 'invalid_message'=>'5 to 12 characters','pattern'=>'.{5,25}'))
            ->add('message','textarea',array('required'=>true, 'invalid_message'=>'10 characters minimum','pattern'=>'.{10,}'))
            ->add('send','submit')->getForm();
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
        $data = $form->getData();
            if ($form->isValid()){
                $emails = $data['destination'];
                $emails = explode(';',$emails);
                $message = \Swift_Message::newInstance()
                    ->setSubject($data['subject'])
                    ->setContentType("text/plain; charset=UTF-8")
                    ->setFrom('Mohamedboubaya1@gmail.com','IOT platfrom')
                    ->setTo('Mohamedboubaya1@gmail.com')
                    ->setBcc($emails)
                    ->setBody($this->renderView(
                        'AppBundle:Promo:email.txt.twig',
                        array('message' => $data['message'])
                    ),'text/plain');
                $this->get('mailer')->send($message);
                $this->addFlash('success', "Your email has been sent to candidates!");
                return $this->redirectToRoute('promo_contact',array('slug'=>$promo->getSlug()));
            }else {
                $this->addFlash('error', "Error encountered while sending your message!");
                return $this->redirectToRoute('promo_contact',array('slug'=>$promo->getSlug()));
            }

    }
        return array(
            'promo' => $promo,
            'form' => $form->createView()
        );
    }

            /**
            *
            * @Route("/{slug}", name="promo_view")
            * @Template()
            *
            */

public function viewAction(Promo $promo)
    {

        $participation = null;
        if ($this->getUser())
        $participation = $this->getDoctrine()->getRepository('AppBundle:Participation')->findBy(array('eleve'=>$this->getUser(),'promo'=>$promo));
        return array(
            'promo' => $promo,
            'participation' => (($participation!=null) and (sizeof($participation)>0)) ? 1 : 0
        );
    }

    /**
     * @Route("/{slug}/submissions", name="promo_submissions")
     * @Security("is_granted('ROLE_PROMO_VIEW', promo)")
     * @Template()
     */
    public function submissionsAction(Promoe $promo)
    {
        $submissions = $this->getDoctrine()->getRepository('AppBundle:Submission')->findMySubmissions($promo->getId(),$this->getUser()->getId());
        return array(
            'promo' => $promo,
            'submissions' => $submissions
        );
    }

    /**
     * @Route("/{slug}/participate", name="promo_participate")
     * @Template()
     * @Security("is_granted('ROLE_USER')")
     */
    public function participateAction(Promo $promo)
    {
        $eleve = $this->getUser();
        $participation = $this->getDoctrine()->getRepository('AppBundle:Participation')->findOneBy(array('promo' => $promo, 'eleve' => $eleve));
        if(!$participation) {

            if($promo->getIsPrivate()){
                $this->addFlash('success', "You will receive an email when the administrator confirm your registration !");
           $participation = new Participation();
            $participation->setPromo($promo);
            $participation->setIsValidated(!$promo->getIsPrivate());
            $participation->setEleve($eleve);
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($participation);
            $em->flush();
                return $this->redirectToRoute('promo_list');
            }
        }
        if ($promo->getEndAt()>(new \DateTime())) {
            $this->addFlash('success', "Your registration has been done successfully.");
            $participation = new Participation();
            $participation->setPromo($promo);
            $participation->setIsValidated(!$promo->getIsPrivate());
            $participation->setEleve($eleve);
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($participation);
            $em->flush();
            return $this->redirectToRoute('promo_view',array('slug'=>$promo->getSlug()));
        }else {
            $this->addFlash('error', "The promo is ended.");
            return $this->redirectToRoute('promo_list');
        }
        return $this->redirectToRoute('projet_list', ['slug' => $promo->getSlug()]);
    }

    /**
     * @Route("/{slug}/scoreboard", name="promo_scoreboard")
     * @Template()
     */
    public function scoreboardAction(Promo $promo)
    {
        if (($promo->getStartAt()>(new \DateTime())) and (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))) {
            return $this->redirectToRoute('promo_view',array('slug'=>$promo->getSlug()));
        }
        $participations = $this->getDoctrine()->getRepository('AppBundle:Participation')->getParticipationForPromo($promo->getId());
        return array(
            'promo' => $promo,
            'participations' => $participations
        );
    }

    /**
     * @Route("/{slug}/edit", name="promo_edit")
     * @Template()
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function editAction(Promo $promo, Request $request)
    {
        $form = $this->createForm(new PromoType(), $promo);
        $form->add('Edit', 'submit');
        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($promo);
            $em->flush();

            $this->addFlash('success', "promo updated !");
            return $this->redirectToRoute('promo_view', ['slug' => $promo->getSlug()]);
        }
        return array(
            'form' => $form->createView(),
            'promo' =>$promo
            );
    }

    /**
     * @Route("/{slug}/delete", name="promo_delete")
     * @Template()
     * @Security("is_granted('ROLE_ADMIN')")
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

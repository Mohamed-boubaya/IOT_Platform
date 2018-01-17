<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Promo;
use AppBundle\Entity\Cookie\Message;
use AppBundle\Entity\Cookie\Cookie;
use AppBundle\Entity\Cookie\SubmittedCookie;
use AppBundle\Entity\Discussion;
use AppBundle\Entity\Response;
use AppBundle\Entity\Submission;
use AppBundle\Entity\projet;
use AppBundle\Form\Cookie\MessageType;
use AppBundle\Form\DiscussionType;
use AppBundle\Form\ResponseType;
use AppBundle\Form\SubmissionType;
use AppBundle\Form\ProjetType;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/promo/{slug}/projet")
 * @Security("is_granted('ROLE_PROMO_PARTICIPATE', promo)")
 */
class ProjetController extends Controller
{
    /**
     * @Route("/{projet}/discussions", name="projet_discussions")
     * @Template()
     */
    public function discussionAction(Promo $promo, $projet, Request $request)
    {
        if (($promo->getStartAt() > (new \DateTime())) and (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))) {
            return $this->redirectToRoute('promo_view', array('slug' => $promo->getSlug()));
        }
        $projet = $this->getDoctrine()->getRepository('AppBundle:Projet')->findOneBy(array('slug' => $projet, 'promo' => $promo));
        $discussions = $this->getDoctrine()->getRepository('AppBundle:Discussion')->findProjetDiscussions($projet->getId());
        $discussion = new Discussion();
        $form = $this->createForm(new DiscussionType(), $discussion);
        $form->add('comment', 'submit');
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $discussion->setProjet($projet);
            $em->persist($discussion);
            $em->flush();

            
            return $this->redirectToRoute('projet_discussions', ['projet' => $projet->getSlug(), 'slug' => $promo->getSlug()]);
        }

        return array(
            'form' => $form->createView(),
            'promo' => $promo,
            'projet' => $projet,
            'discussions' => $discussions
        );
    }


    /**
     * @Route("/", name="projet_list")
     * @Template()
     */
    public function indexAction(Promo $promo)
    {
        if (($promo->getStartAt() > (new \DateTime())) and (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))) {
            return $this->redirectToRoute('promo_view', array('slug' => $promo->getSlug()));
        }
//
//        
        $projets = $this->getDoctrine()->getRepository('AppBundle:Projet')->findAllWithStat($promo->getId());



//        $rsm = new ResultSetMappingBuilder($this->getDoctrine()->getEntityManager());
//        't');
//        $rsm->addJoinedEntityFromClassMetadata('AppBundle\Entity\Submission', 's', 't', 'submissions', array('id' => 'sub_id', 'createdAt' => 'sub_createdAt'));
////
////        dump($projects);
//        $query = $this->getDoctrine()->getEntityManager()->createNativeQuery($sql, $rsm);
//
//        $users = $query->getResult();


        return array(
            'promo' => $promo,
            'projets' => $projets,
//'categories' =>$projets,
        );
    }

    /**
     * @Route("/new", name="projet_new")
     * @Template()
     */
    public function newAction(promo $promo, Request $request)
    {
        $projet = new Projet();
        $form = $this->createForm(new ProjetType(), $projet);
        $form->add('add', 'submit');
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($projet);
            $promo->addProjet($projet);
            $em->flush();

            $this->addFlash('success', "Projet added !");
            return $this->redirectToRoute('projet_view', ['slug' => $promo->getSlug(), 'slug_projet' => $projet->getSlug()]);
        }

        return array(
            'form' => $form->createView(),
            'promo' => $promo
        );
    }

    /**
     * @Route("/delete", name="projet_delete")
     * @Template()
     */
    public function deleteAction()
    {
        return array(// ...
        );
    }


    /**
     * @Route("/{slug_projet}/edit", name="projet_edit")
     * @Template()
     */
    public function editAction(Promo $promo, $slug_projet, Request $request)
    {
        $projet = $this->getDoctrine()->getRepository('AppBundle:Projet')->findOneBy(array('slug' => $slug_projet, 'promo' => $promo));
        if (!$projet)
            throw new NotFoundHttpException();
        $form = $this->createForm(new ProjetType(), $projet);
        $form->add('edit', 'submit');
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->flush();
            $this->addFlash('success', "Projet updated !");
        }

        $response = new Response();
        $form_response = $this->createForm(new ResponseType(), $response, array('action' => $this->generateUrl('projet_response', ['slug' => $promo->getSlug(), 'slug_projet' => $slug_projet])));
        $form_response->add('add', 'submit');

        return array(
            'form' => $form->createView(),
            'promo' => $promo,
            'projet' => $projet,
            'form_response' => $form_response->createView(),
            'responses' => $projet->getResponses()
        );
    }

    /**
     * @Route("/{slug_projet}/notifiy", name="projet_notifiy")
     * @Method({"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function contactAction(Promo $promo, $slug_projet, Request $request)
    {
        if ($request->isMethod('POST')) {
            $projet = $this->getDoctrine()->getRepository('AppBundle:Projet')->findOneBySlug($slug_projet);
            $emails2 = $this->getDoctrine()->getRepository('AppBundle:Participation')->getParticipantsEmails($promo->getId());
            $list = array();
            foreach($emails2 as $e){
                $list[]=$e['email'];
            }
            $emails=array('mohamedboubaya1@gmail.com');
            $message = \Swift_Message::newInstance()
                ->setSubject('New Projet: ' . $projet->getName())
                ->setContentType("text/plain; charset=UTF-8")
                ->setFrom('mohamedboubaya1@gmail.com', 'IOT Plarform')
                ->setTo('mohamedboubaya1@gmail.com')
                ->setBcc($list)
                ->setBody($this->renderView(
                    'AppBundle:Projet:email.txt.twig',
                    array('projet' => $projet)
                ), 'text/plain');
            try{
                $this->get('mailer')->send($message);
                $this->addFlash('success', "Your email has been sent to candidates!");
            }catch (\Exception $ex){
                $this->addFlash('error', "Error encountered while sending your message!");
            }
        }

        return $this->redirectToRoute('projet_edit', array('slug' => $promo->getSlug(),'slug_projet'=>$slug_projet));
    }

    /**
     * @Route("/{slug_projet}/response", name="projet_response")
//     * @Template()
     */
    public function responseAction(Promo $promo, $slug_projet, Request $request)
    {
        if (($promo->getStartAt()>(new \DateTime())) and (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))) {
            return $this->redirectToRoute('promo_view',array('slug'=>$promo->getSlug()));
        }
        $projet = $this->getDoctrine()->getRepository('AppBundle:Projet')->findOneBy(array('slug' => $slug_projet, 'promo' => $promo));
        if(!$projet)
            throw new NotFoundHttpException();

        $response = new Response();
        $form = $this->createForm(new ResponseType(), $response);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getEntityManager();
            $response->setProjet($projet);
            $em->persist($response);
            $projet->setQuestionsCount($projet->getQuestionsCount()+1);
            $em->persist($projet);
            $em->flush();

            $this->addFlash('success', "Flag added !");
        }
        return $this->redirectToRoute('projet_edit', ['slug' => $promo->getSlug(), 'slug_projet' => $slug_projet]);
    }

    /**
     * @Route("/{slug_projet}", name="projet_view")
     * @Template()
     */
    public function viewAction(Promo $promo, $slug_projet, Request $request)
    {
        $currentDate = new \DateTime();
        if (($promo->getStartAt()>$currentDate) and (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))) {
            return $this->redirectToRoute('promo_view',array('slug'=>$promo->getSlug()));
        }
        $projet = $this->getDoctrine()->getRepository('AppBundle:projet')->findOneBy(array('slug' => $slug_projet, 'promo' => $promo));
        if(!$projet)
            throw new NotFoundHttpException();
        if (!(($currentDate>$promo->getEndAt())||($currentDate>$projet->getEndAt()))) {

            $submission = new Submission();
            $form = $this->createForm(new SubmissionType(), $submission);
            $form->add('submit', 'submit');
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $submission->setProjet($projet);
                $submission->setIp($request->getClientIp());
                if ($projet->getId()==$this->container->getParameter('projet_king_jungle_id')){
                    //$this->addFlash('success', "Hello sir !");
                    $cookies = $this->getDoctrine()->getRepository('AppBundle:Cookie\Cookie')->findCookies($submission->getFlag(), $this->getUser()->getId());
                    if (($cookies==null)||(sizeof($cookies)==0)) {
                        $this->addFlash('error', "Bad response !");
                        return $this->redirectToRoute('projet_view', array('slug' => $promo->getSlug(), 'slug_projet' => $projet->getSlug()));
                    }
                    $countSubmittedCookie = $this->getDoctrine()->getRepository('AppBundle:Cookie\SubmittedCookie')->countMySubmittedCookies($this->getUser()->getId());
                    if ($countSubmittedCookie > 9){
                        $this->addFlash('error', "You eated a lot of cookies!  You'll become fate !");
                        return $this->redirectToRoute('projet_view', array('slug' => $promo->getSlug(), 'slug_projet' => $projet->getSlug()));
                    }
                    //valid
                    if ($cookies[0]->getTokens()==0){
                        $this->addFlash('error', "Your cookie was already eaten, sorry Mr Hungry!");
                        return $this->redirectToRoute('projet_view', array('slug' => $promo->getSlug(), 'slug_projet' => $projet->getSlug()));
                    }
                    $responses = $this->getDoctrine()->getRepository('AppBundle:Response')->findBy(array('projet'=>$projet->getId()));
                    if (($responses==null)||(sizeof($responses)==0))
                        throw new NotFoundHttpException("");
                    $submittedCookies = $this->getDoctrine()->getRepository('AppBundle:Cookie\SubmittedCookie')->findBy(array(
                        'eleve'=>$this->getUser(),
                        'cookie'=>$cookies[0]
                    ));
                    if (($submittedCookies!=null)&&(sizeof($submittedCookies)>0)){
                        $this->addFlash('error', "You've already eaten the cookie!");
                        return $this->redirectToRoute('projet_view', array('slug' => $promo->getSlug(), 'slug_projet' => $projet->getSlug()));

                    }
                    $cookies[0]->setTokens($cookies[0]->getTokens()-1);
                    $em->persist($cookies[0]);
                    $em->flush();

                    $response = $responses[0];
                    $submission->setResponse($response);

                    $em->persist($submission);
                    $em->flush();




                    $submittedCookie = new SubmittedCookie();
                    $submittedCookie->setCookie($cookies[0]);

                    $submittedCookie->setEleve($this->getUser());
                    $em->persist($submittedCookie);
                    $em->flush();
                    $participations = $this->getDoctrine()->getRepository('AppBundle:Participation')->findBy(array('eleve'=>array($this->getUser()->getId(),$cookies[0]->getEleve()),'promo'=>$promo->getId()));
                    if ($participations[0]->getEleve()==$this->getUser()){
                        $participations[0]->setKeyword($participations[0]->getKeyword()+10);
                        $participations[1]->setKeyword($participations[1]->getKeyword()-10);
                    }else {
                        $participations[1]->setKeyword($participations[1]->getKeyword()+10);
                        $participations[0]->setKeyword($participations[0]->getKeyword()-10);
                    }
                    $em->persist($participations[0]);
                    $em->persist($participations[1]);
                    $em->flush();
                    $this->addFlash('success', "Good response, you gain 10 points from ".$cookies[0]->getEleve()->getUsername()."!");
                    return $this->redirectToRoute('projet_view', array('slug' => $promo->getSlug(), 'slug_projet' => $projet->getSlug()));
                }else {
                    $response = $this->getDoctrine()->getRepository('AppBundle:Response')->findOneBy(array(
                        'flag' => $submission->getFlag(),
                        'projet' => $projet
                    ));

                    if ($response != null) {
                        $submission->setResponse($response);
                        $participation = $this->getDoctrine()->getRepository('AppBundle:Participation')->findOneBy(array('promo' => $promo, 'eleve' => $this->getUser()));
                        $res = $this->getDoctrine()->getRepository('AppBundle:Submission')->findBy(array(
                            'response' => $response,
                            'projet' => $projet,
                            'eleve' => $this->getUser()
                        ));
                        $responses = $this->getDoctrine()->getRepository('AppBundle:Response')->findBy(array(
                            'projet' => $projet
                        ));
                        $keyworde = $response->getProjet()->getKeyword() / sizeof($responses);
                        if (($res == null) or (sizeof($res) == 0)) {
                            $participation->setKeyword($participation->getKeyword() + $keyworde);
                            $this->addFlash('success', "Good response, you gain $Keyword points !");
                            $em->persist($submission);
                            $em->flush();
                            return $this->redirectToRoute('projet_view', array('slug' => $promo->getSlug(), 'slug_projet' => $projet->getSlug()));
                        } else {
                            $this->addFlash('success', "You've already got $Keyword points !");
                            $em->persist($submission);
                            $em->flush();
                            return $this->redirectToRoute('projet_view', array('slug' => $promo->getSlug(), 'slug_projet' => $projet->getSlug()));
                        }
                    }
                }

                $em->persist($submission);
                $em->flush();
                $this->addFlash('error', "Bad response !");
                return $this->redirectToRoute('projet_view', array('slug' => $promo->getSlug(), 'slug_projet' => $projet->getSlug()));

            }

            return array(
                'promo' => $promo,
                'projet' => $projet,
                'form' => $form->createView()
            );
        }else {
            return array(
                'promo' => $promo,
                'projet' => $projet
            );
        }
    }

    /**
     * @Route("/{slug_projet}/submissions", name="projet_submissions")
     * @Template()
     */
    public function submissionsAction(Promo $promo, $slug_projet)
    {
        if (($promo->getStartAt()>(new \DateTime())) and (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))) {
            return $this->redirectToRoute('promo_view',array('slug'=>$promo->getSlug()));
        }
        $projet = $this->getDoctrine()->getRepository('AppBundle:Projet')->findOneBy(array('slug' => $slug_projet, 'promo' => $promo));
        if(!$projet)
            throw new NotFoundHttpException();
        $submissions = $this->getDoctrine()->getRepository('AppBundle:Submission')->findMySubmissionsForProjet($promo->getId(),$this->getUser()->getId(),$projet->getId());
        return array(
            'promo' => $promo,
            'projet' => $projet,
            'submissions' => $submissions
        );
    }

}

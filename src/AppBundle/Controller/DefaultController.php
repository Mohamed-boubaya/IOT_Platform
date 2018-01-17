<?php

namespace AppBundle\Controller;
use AppBundle\Entity\Cookie\Cookie;
use AppBundle\Entity\Cookie\Message;
use AppBundle\Form\Cookie\MessageType;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Proxies\__CG__\AppBundle\Entity\Promo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     * @Template("AppBundle::index.html.twig")
     */
    public function indexAction()
    {
        $next = $this->getDoctrine()->getRepository('AppBundle:Promo')->findNextPromo();
        $now = $this->getDoctrine()->getRepository('AppBundle:Promo')->findCurrentPromos();
        if (($next!=null)and(sizeof($next)>0)){
            $next=$next[0];
        }else $next=null;
        return array('now'=> $now, 'next'=> $next
        );
    }



    /**
     * @Route("/faq", name="faq")
     * @Template("AppBundle::faq.html.twig")
     */
    public function faqAction()
    {
        return array(
        );
    }

    /**
     * @Route("/rules", name="rules")
     * @Template("AppBundle::rules.html.twig")
     */
    public function rulesAction()
    {
        return array(
        );
    }

    /**
     * @Route("/about", name="about")
     * @Template("AppBundle::about.html.twig")
     */
    public function aboutAction()
    {
        return array(
        );
    }

    /**
     * @Route("/email", name="email")
     * @Template("::email.html.twig")
     */
    public function emailAction()
    {
        return array(
        );
    }


 /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("sendmail", name="sendmail")
     * @Template("AppBundle::rules.html.twig")
     */
    public function sendmailAction()
    {
            $message = \Swift_Message::newInstance()
                ->setSubject('Test')
                ->setContentType("plain/text")
                ->setFrom('ctfg2foss@gmail.com')
                ->setTo('ahmedmaalej74@gmail.com')
                ->setBody("Hello");
            $this->get('mailer')->send($message);
            $session = $this->container->get('session');
            $session->getFlashBag()->set('success', "Votre message a été envoyé!");
      
    }

    /**
     * @Template("AppBundle::Assets/cookie.html.twig")
     * @Route("/assets/jungle", name="projet_cookie")
     * @Security("is_granted('ROLE_USER')")
     */
    public function cookieAction(Request $request)
    {
        $projet = $this->getDoctrine()->getRepository('AppBundle:Projet')->findOneById($this->container->getParameter('projet_king_jungle_id'));
        $Promo = $projet->getPromo();
        $cook = md5($this->container->getParameter('secret').$this->getUser()->getId());
        unset($_COOKIE['projet']);

        setcookie('projet',$cook,time()+(5000*60));
        $findedCookie = $this->getDoctrine()->getRepository('AppBundle:Cookie\Cookie')->findByEleve($this->getUser());
        if (($findedCookie==null)||(sizeof($findedCookie)==0)) {
            $em = $this->getDoctrine()->getEntityManager();
            $cookie = new Cookie();
            $cookie->setCookie($cook);
            //echo "hello guest";
            $em->persist($cookie);
            $em->flush();
        }
        //echo md5($this->container->getParameter('secret').$this->getUser()->getId());
        $message = new Message();
        $form = $this->createForm(new MessageType(), $message);
        $form->add('send', 'submit');
        $form->handleRequest($request);
        if($form->isValid()){
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($message);
            $em->flush();
            //$this->addFlash('success', "Message successfuly sent!");
        }
        $msgs = $this->getDoctrine()->getRepository('AppBundle:Cookie\Message')->findLastMessages();
        return array('form'=>$form->createView(),'msgs'=>$msgs,'Promo'=>$Promo,'projet'=>$projet);

    }
}

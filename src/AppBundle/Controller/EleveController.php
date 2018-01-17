<?php

namespace AppBundle\Controller;

use AppBundle\Form\EleveProfileType;
use FOS\UserBundle\Controller\ProfileController as BaseController;
use AppBundle\Form\EleveProfileEditType;
use AppBundle\Form\EleveRegistrationType;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;

class EleveController extends Controller
{
    /**
     * @Route("/eleves", name="eleves")
     * @Template("AppBundle:Eleve:index.html.twig")
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
        $eleves = $this->getDoctrine()->getRepository('AppBundle:Eleve')->findAllEleves($filter,$filterValue,$order);
        return array(
            'eleves' => $eleves,
        );
    }

    /**
     * @Route("/eleve/{slug}", name="view_eleve")
     * @Security("is_granted('ROLE_USER')")
     * @Template("FOSUserBundle:Profile:show.html.twig")
     */
    public function viewAction($slug, Request $request)
    {
        $user = $this->getUser();
        if ($user==null) throw new HttpException("");
        if ($user->getSlug()===$slug)
            return $this->redirectToRoute('fos_user_profile_show');
        $eleve = $this->getDoctrine()->getRepository('AppBundle:Eleve')->findEleveBySlug($slug);
        if (!$eleve->isCondidate())
            throw new HttpException("");
        return array(
            'user' => $eleve,
        );
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     * @Template("FOSUserBundle:Profile:show.html.twig")
     */
    public function profileAction(Request $request)
    {
        $user = $this->getUser();
        if ($user==null) throw new HttpException("");
        $eleve = $this->getDoctrine()->getRepository('AppBundle:Eleve')->findEleveBySlug($user->getSlug());
        return array(
            'user' => $eleve,
        );
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     * @Template("FOSUserBundle:Profile:edit.html.twig")
     */
    public function profileEditAction(Request $request)
    {
        $eleve = $this->getDoctrine()->getRepository('AppBundle:Eleve')->findEleveBySlug($this->getUser()->getSlug());
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        
        $form = $this->container->get('fos_user.profile.form');
        //$form->add('edit','submit');
        $formHandler = $this->container->get('fos_user.profile.form.handler');

        $process = $formHandler->process($user);
        if ($process) {

            //$this->setFlash('fos_user_success', 'profile.flash.updated');
            $this->addFlash('success', "Profile edited with success!");
        }

        return $this->container->get('templating')->renderResponse(
            'FOSUserBundle:Profile:edit.html.'.$this->container->getParameter('fos_user.template.engine'),
            array('form' => $form->createView(),'user'=>$eleve)
        );

    }

    /**
     * @Security("is_granted('ROLE_USER')")
     * Change user password
     */
    public function changePasswordAction()
    {
        $eleve = $this->getDoctrine()->getRepository('AppBundle:Eleve')->findEleveBySlug($this->getUser()->getSlug());
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->container->get('fos_user.change_password.form');
        $formHandler = $this->container->get('fos_user.change_password.form.handler');

        $process = $formHandler->process($user);
        if ($process) {
            //$this->setFlash('fos_user_success', 'change_password.flash.success');
            $this->addFlash('success', "Password edited with success!");
            //return new RedirectResponse($this->getRedirectionUrl($user));
        }

        return $this->container->get('templating')->renderResponse(
            'FOSUserBundle:ChangePassword:changePassword.html.'.$this->container->getParameter('fos_user.template.engine'),
            array('form' => $form->createView(),
                'user' => $eleve
                )
        );
    }
}

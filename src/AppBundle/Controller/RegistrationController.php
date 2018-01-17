<?php

namespace AppBundle\Controller;

use AppBundle\Services\Avatar;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\Request;use Symfony\Component\HttpFoundation\Response;

class RegistrationController extends BaseController {

    public function registerAction(){

        if (true === $this->container->get('security.context')->isGranted('ROLE_USER')) {
            return new RedirectResponse($this->container->get('router')->generate('index'));
    }


            //$this->setFlash('error', 'Registrations are over !');
            //return new RedirectResponse($this->container->get('router')->generate('index'));

        $form = $this->container->get('fos_user.registration.form');
        $formHandler = $this->container->get('fos_user.registration.form.handler');
        $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');

        $process = $formHandler->process($confirmationEnabled);
        if ($process) {
            $user = $form->getData();
            

   
            /*****************************************************
             * Add new functionality (e.g. log the registration) *
             *****************************************************/

            if ($confirmationEnabled) {
                $this->container->get('session')->set('fos_user_send_confirmation_email/email', $user->getEmail());
                $route = 'fos_user_registration_check_email';

            } else {
$req = $this->container->get('request');
                $this->authenticateUser($user, new Response());
                $route = 'fos_user_registration_confirmed';

            }

            $avatar = new Avatar($user->getId(), 5, 100);
            $avatar->save('img/avatar/'.$user->getId().'.png');

            $this->setFlash('fos_user_success', 'registration.flash.user_created');
            $url = $this->container->get('router')->generate($route);

            return new RedirectResponse($url);
        }

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:register.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
<?php
namespace AppBundle\Security;

use Symfony\Component\Security\Core\Authorization\Voter\AbstractVoter;
use Symfony\Component\Security\Core\User\UserInterface;

class PromoVoter extends AbstractVoter {
    const VIEW = 'ROLE_PROMO_VIEW';
    const PARTICIPATE   = 'ROLE_PROMO_PARTICIPATE';

    protected function getSupportedAttributes()
    {
        return array(self::VIEW, self::PARTICIPATE);
    }

    protected function getSupportedClasses()
    {
        return array('AppBundle\Entity\Promo');
    }

    protected function isGranted($attribute, $promo, $user = null)
    {
        if ($attribute === self::VIEW) {
//            if($challenge->getIsPrivate() == false)
                return true;
//            else {
//                if (!$user instanceof UserInterface) {
//                    return false;
//                }
//                foreach ($challenge->getParticipations() as $participation) {
//                    if ($participation->getTeam()->getId() == $user->getId() && $participation->getIsValidated())
//                        return true;
//                }
//            }
        }

        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($attribute === self::PARTICIPATE) {
            foreach($promo->getParticipations() as $participation){
                if($participation->getEleve()->getId() == $user->getId() && $participation->getIsValidated())
                    return true;
            }
        }

        return false;
    }

}
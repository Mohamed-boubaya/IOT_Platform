<?php

namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Tests\Extension\Core\Type\CollectionTypeTest;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Validator\Constraints\Collection;

class EleveProfileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
  $builder

            ->add('fname', null, array('label' => 'form.fname', 'translation_domain' => 'FOSUserBundle'))
            ->add('name', null, array('label' => 'form.name', 'translation_domain' => 'FOSUserBundle'))
            ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
     
            ->add('organisation')
            ->add('country','genemu_jqueryselect2_country',array('data'=>'TN'))
//->add('captcha', 'genemu_captcha',array('mapped' => false,))
          ->add('linkdin')
           
           
        ;

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Eleve'
        ));
    }


    public function getParent()
    {
        return 'fos_user_profile';
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_eleve_profile';
    }
}

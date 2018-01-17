<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EleveRegistrationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
            ->add('fname', null, array('label' => 'form.fname', 'translation_domain' => 'FOSUserBundle'))
            ->add('name', null, array('label' => 'form.name', 'translation_domain' => 'FOSUserBundle'))
            ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'form.password'),
                'second_options' => array('label' => 'form.password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
            ->add('organisation')
            ->add('country','genemu_jqueryselect2_country',array('data'=>'TN'))
//->add('captcha', 'genemu_captcha',array('mapped' => false,))
          ->add('linkdin')
        ->add('competitions','text',array('pattern'=>'([a-zA-Z]+([ ][a-zA-Z]+)*,[ ]?){2,4}[A-Za-z ]+([ ][a-zA-Z]+)*','invalid_message'=>'The Student can have of 3 to 5 competitions'));

      
            $builder->get('competitions')
            ->addModelTransformer(new CallbackTransformer(
         // var_dump($builder->get('competitions'));
                function ($originalCompetitions) {
                   return $originalCompetitions;
                },
                function ($submittedCompetitions) {
                    $array = explode(',',$submittedCompetitions);
                    $submittedCompetitions = array();
                    foreach ($array as $competition){
                        $submittedCompetitions[] = strtoupper(trim($competition));
                    }
                    return $submittedCompetitions;
                }
            ))
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
        return 'fos_user_registration';
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_eleve_registration';
    }
}

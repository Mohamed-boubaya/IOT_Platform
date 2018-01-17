<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PromoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('startAt', 'datetime', array(
                'widget' => 'single_text',
                'format' => 'yyyy/MM/dd HH:mm'
            ))
            ->add('endAt', 'datetime', array(
                'widget' => 'single_text',
                'format' => 'yyyy/MM/dd HH:mm'
            ))
            ->add('isPrivate')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Promo'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_promo';
    }
}

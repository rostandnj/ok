<?php

namespace Sdz\ATIBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonnelType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom','text',array('required' =>true))
            ->add('noPieceIdent','text',array('required' =>true))
            ->add('poste','text',array('required' =>true))
            ->add('villeResidence','text',array('required' =>true))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sdz\ATIBundle\Entity\Personnel'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sdz_atibundle_personnel';
    }
}

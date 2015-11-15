<?php

namespace Sdz\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
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
            ->add('username','text',array('required' =>true))
            ->add('password','password',array('required' =>true,'data'=>'','attr'=>array('type'=>'password')))
            ->add('roles','entity',array('class' => 'SdzUserBundle:Role','property'=> 'role'))
            ->add('isActive','checkbox',array('required' =>false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sdz\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sdz_userbundle_user';
    }
}

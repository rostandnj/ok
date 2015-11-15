<?php

namespace Sdz\ATIBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ProduitMagasinAjoutType extends AbstractType
{



    public function __construct($options = null) 
    {
        $this->options = $options;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
       

       $builder->add('quantite','integer',array('required' =>true))
               ->add('produit','entity',array('class' => 'SdzATIBundle:Produit','property'=> 'nom'))
               ->add('observation','textarea',array('required' =>true));

                     
                         
                         


             
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sdz\ATIBundle\Entity\ProduitMagasin'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sdz_atibundle_produitMagasin';
    }
}

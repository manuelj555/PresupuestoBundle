<?php

namespace K2\PresupuestoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DescripcionForm extends AbstractType
{

    public function getName()
    {
        return "descripcion";
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("descripcion")
                ->add("posicion", "hidden")
                ->add("cantidad")
                ->add("precio");
        
        
    }

    public function setDefaultOptions(\Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'data_class' => "K2\\PresupuestoBundle\\Entity\\DescripcionPresupuestos",
        ));
    }

}
<?php

namespace K2\PresupuestoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ManoDeObraForm extends AbstractType {

    public function getName() {
        return "mano_de_obra";
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('descripcion', 'textarea', array(
                    'label' => "Descripción",
                    'error_bubbling' => true,
                    'attr' => array(
                        'class' => "validate[required]",
                    ),
                ))
                ->add('medidas', 'entity', array(
                    'label' => "Unidad de Medida",
                    'class' => "K2\\PresupuestoBundle\\Entity\\Medidas",
                    'property' => 'medida',
                    'error_bubbling' => true,
                    'attr' => array(
                        'class' => "validate[required]",
                    ),
                ))
                ->add('tiposDeObras', 'entity', array(
                    'label' => "Tipo",
                    'class' => "K2\\PresupuestoBundle\\Entity\\TiposDeObras",
                    'property' => 'nombre',
                    'error_bubbling' => true,
                    'attr' => array(
                        'class' => "validate[required]",
                    ),
                ))
                ->add('precio', 'text', array(
                    'label' => "Precio",
                    'error_bubbling' => true,
                    'attr' => array(
                        'class' => "validate[required,custom[number]]",
                    ),
                ))
        ;
    }

    public function setDefaultOptions(\Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver) {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }

}
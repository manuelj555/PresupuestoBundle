<?php

namespace K2\PresupuestoBundle\Form;

use K2\PresupuestoBundle\Entity\ManoDeObraMaterial;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ManoDeObraForm extends AbstractType
{

    public function getName()
    {
        return "mano_de_obra";
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('descripcion', 'textarea', array(
                    'label' => "Descripción",
                    'error_bubbling' => true,
                    'attr' => array(
                        'class' => "validate[required] form-control",
                        'placeholder' => "Descripción",
                    ),
                ))
                ->add('medidas', 'entity', array(
                    'label' => "Unidad de Medida",
                    'class' => "K2\\PresupuestoBundle\\Entity\\Medidas",
                    'property' => 'medida',
                    'error_bubbling' => true,
                    'attr' => array(
                        'class' => "validate[required] form-control",
                    ),
                ))
                ->add('tiposDeObras', 'entity', array(
                    'label' => "Tipo",
                    'class' => "K2\\PresupuestoBundle\\Entity\\TiposDeObras",
                    'property' => 'nombre',
                    'error_bubbling' => true,
                    'attr' => array(
                        'class' => "validate[required] form-control",
                    ),
                ))
                ->add('precio', 'text', array(
                    'label' => "Precio",
                    'error_bubbling' => true,
                    'attr' => array(
                        'class' => "validate[required,custom[number]] form-control",
                    ),
                ))
                ->add('materiales', 'collection', array(
                    'type' => new ManoDeObraMaterialType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'error_bubbling' => true,
                    'by_reference' => false,
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'data_class' => 'K2\\PresupuestoBundle\\Entity\\ManosDeObra',
        ));
    }

}

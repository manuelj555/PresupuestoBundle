<?php

namespace K2\PresupuestoBundle\Form;

use K2\PresupuestoBundle\Form\UnidadType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 *
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class MaterialType extends AbstractType
{

    public function getName()
    {
        return 'material';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nombre')
                ->add('precio')
                ->add('unidades', 'collection', array(
                    'type' => new UnidadType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'data_class' => 'K2\\PresupuestoBundle\\Entity\\Materiales',
        ));
    }

}

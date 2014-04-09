<?php

/*
 * This file is part of the Manuel Aguirre Project.
 *
 * (c) Manuel Aguirre <programador.manuel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace K2\PresupuestoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 *
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class UnidadType extends AbstractType
{

    public function getName()
    {
        return 'material_unidad';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('unidad', null, array(
                    'empty_value' => false,
                ))
                ->add('factor')
                ->add('isBase', 'radio', array(
                    'required' => false,
                ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'data_class' => 'K2\\PresupuestoBundle\\Entity\\Unidades',
        ));
    }

}

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

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 *
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class UnidadesEntityType extends AbstractType
{

    public function getParent()
    {
        return 'entity';
    }

    public function getName()
    {
        return 'unidades_entity';
    }

    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->setAttribute('query_builder', function(EntityRepository $er) use ($options) {
            return $er->createQueryBuilder('unidades')
                            ->select('unidades, unidad.nombre')
                            ->join('unidades.unidad', 'unidad')
                            ->where('unidades.material = :material')
                            ->setParameter('material', $options['material']);
        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(array('material'));
        
        $resolver->setDefaults(array(
            'class' => 'K2\\PresupuestoBundle\\Entity\\Unidades',
            'property' => 'unidadName',
        ));
    }

}

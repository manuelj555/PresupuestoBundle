<?php

namespace K2\PresupuestoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 *
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class ManoDeObraMaterialType extends AbstractType
{

    public function getName()
    {
        return 'manodeobra_material';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('material', 'entity', array(
                    'class' => 'PresupuestoBundle:Materiales',
                    'label' => 'Material',
                    'empty_value' => 'Seleccione',
                ))
                ->add('cantidad', 'text', array(
                    'label' => 'Cantidad',
                ))
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'buildDefaultUnidad'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'buildDefaultUnidad'));
    }

    public function buildDefaultUnidad(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (is_array($data)) {
            $material = $data['material'];
        } elseif (is_object($data)) {
            $material = $data->getMaterial();
        } else {
            $material = null;
        }

        $options = array('material' => $material);

        if (!$material) {
            $options['choices'] = array();
        }
        
        $form->add('defaultUnidad', new UnidadesEntityType(), $options);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'data_class' => 'K2\\PresupuestoBundle\\Entity\\ManoDeObraMaterial',
        ));
    }

}

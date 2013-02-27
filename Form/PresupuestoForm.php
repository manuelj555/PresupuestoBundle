<?php

namespace K2\PresupuestoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use K2\PresupuestoBundle\Form\DescripcionForm;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use K2\PresupuestoBundle\Util;

class PresupuestoForm extends AbstractType
{

    public function getName()
    {
        return "presupuesto";
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("titulo")
                ->add("descripciones", "collection", array(
                    'type' => new DescripcionForm(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ));

        $builder->addEventListener(FormEvents::PRE_BIND, function(FormEvent $event) {
                    $data = $event->getData();
                    if (isset($data['descripciones'])) {
                        $data['descripciones'] = array_values(Util::removeEmpty($data['descripciones']));
                        $event->setData($data);
                    }
                });
    }

    public function setDefaultOptions(\Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }

}
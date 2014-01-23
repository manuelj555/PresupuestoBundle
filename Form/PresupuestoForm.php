<?php

namespace K2\PresupuestoBundle\Form;

use Doctrine\ORM\EntityManager;
use JMS\Serializer\SerializerInterface;
use K2\PresupuestoBundle\Form\DescripcionForm;
use K2\PresupuestoBundle\Form\Listener\PresupuestoListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PresupuestoForm extends AbstractType
{

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     *
     * @var SerializerInterface
     */
    protected $serializer;

    function __construct(EntityManager $em, SerializerInterface $serializer)
    {
        $this->em = $em;
        $this->serializer = $serializer;
    }

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

        $builder->addEventListener(FormEvents::PRE_BIND
                , array(new PresupuestoListener(), 'onPreBind'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $items = $this->em->getRepository('PresupuestoBundle:ManosDeObra')
                ->findAllArray();

        $items = $this->serializer->serialize($items, 'json');

        $view->vars['manos_de_obra'] = $items;
    }

}

<?php

namespace K2\PresupuestoBundle\Model;

use Doctrine\ORM\EntityManager;
use K2\PresupuestoBundle\Entity\PresupuestoRepository;
use K2\PresupuestoBundle\Entity\Presupuestos;
use K2\PresupuestoBundle\Form\PresupuestoForm;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Description of PresupuestoManager
 *
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class PresupuestoManager
{

    /**
     *
     * @var EntityManager
     */
    protected $em;

    /**
     *
     * @var FormFactoryInterface
     */
    protected $formFactory;
    protected $presupuestoRepository;

    function __construct(EntityManager $em, FormFactoryInterface $formFactory, $presupuestoRepository)
    {
        $this->em = $em;
        $this->formFactory = $formFactory;
        $this->presupuestoRepository = $presupuestoRepository;
    }

    /**
     * 
     * @return PresupuestoRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository($this->presupuestoRepository);
    }

    public function getForm(Presupuestos $presupuesto)
    {
        $presupuesto || $presupuesto = new Presupuestos();

        return $this->formFactory->create(new PresupuestoForm(), $presupuesto);
    }

    public function save(Presupuestos $presupuesto)
    {
        $descripcionesOriginales = $presupuesto->getDescripciones()->toArray();

        $presupuesto->guardar($this->em, $descripcionesOriginales);
        $this->em->flush();
    }

}

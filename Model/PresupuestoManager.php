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
class PresupuestoManager extends AbstractManager
{

    public function getForm($object = null)
    {
        $object || $object = new Presupuestos();
        return parent::getForm($object);
    }

    public function save(Presupuestos $presupuesto)
    {
        $total = 0;
        foreach ($presupuesto->getDescripciones() as $des) {
            $des->calculateSubtotal();
            $total += $des->getSubtotal();
            $this->em->persist($des);
        }

        $presupuesto->setTotal($total);
        $this->em->persist($presupuesto);

        $this->em->flush();
    }

    protected function getFormType()
    {
        return 'presupuesto';
    }

}

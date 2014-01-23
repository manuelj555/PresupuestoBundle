<?php

namespace K2\PresupuestoBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Description of ManoDeObraRepository
 *
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class PresupuestoRepository extends EntityRepository
{

    public function queryAll()
    {
        return $this->createQueryBuilder('p')
                        ->getQuery();
    }

}

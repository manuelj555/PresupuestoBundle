<?php

namespace K2\PresupuestoBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Description of ManoDeObraRepository
 *
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class ManoDeObraRepository extends EntityRepository
{

    public function queryAllManosDeObra($findDescription = null)
    {
        $dql = "SELECT mdo,tip,med 
                FROM PresupuestoBundle:ManosDeObra mdo
                JOIN mdo.medidas med
                JOIN mdo.tiposDeObras tip
                WHERE mdo.descripcion LIKE :description
                ORDER BY mdo.descripcion";

        return $this->getEntityManager()
                        ->createQuery($dql)
                        ->setParameter('description', "%$findDescription%");
    }

}

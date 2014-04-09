<?php

namespace K2\PresupuestoBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Description of ManoDeObraRepository
 *
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class UnidadesRepository extends EntityRepository
{

    public function getForMaterialQuery($material)
    {
        return $this->createQueryBuilder('unidades')
                        ->select('unidades.id, unidad.nombre')
                        ->join('unidades.unidad', 'unidad')
                        ->where('unidades.material = :mat')
                        ->setParameter('mat', $material)
                        ->getQuery()
        ;
    }

}

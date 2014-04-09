<?php

namespace K2\PresupuestoBundle\Model;

use Doctrine\ORM\EntityManager;
use K2\PresupuestoBundle\Entity\ManoDeObraRepository;
use K2\PresupuestoBundle\Entity\ManosDeObra;
use K2\PresupuestoBundle\Form\ManoDeObraForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Description of ManoObraManager
 *
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class ManoDeObraManager extends AbstractManager
{

    /**
     * @deprecated usar getRepository directamente
     * @return ManoDeObraRepository
     */
    public function getManoDeObraRepository()
    {
        return $this->getRepository();
    }

    public function getAllManosDeObra()
    {
        return $this->getManoDeObraRepository()
                        ->queryAllManosDeObra()
                        ->getArrayResult();
    }

    public function persist(ManosDeObra $manoDeObra)
    {
        $this->em->persist($manoDeObra);

        foreach ($manoDeObra->getMateriales() as $material) {
//            $material->setManoDeObra($manoDeObra);
            $this->em->persist($material);
        }

        $this->em->flush();
    }

    protected function getFormType()
    {
        return new ManoDeObraForm();
    }

}

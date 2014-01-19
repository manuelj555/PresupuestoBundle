<?php

namespace K2\PresupuestoBundle\Model;

use Doctrine\ORM\EntityManager;
use K2\PresupuestoBundle\Entity\ManoDeObraRepository;
use K2\PresupuestoBundle\Entity\ManosDeObra;
use K2\PresupuestoBundle\Form\ManoDeObraForm;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Description of ManoObraManager
 *
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class ManoDeObraManager
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
    protected $manoDeObraRepository;
    protected $presupuestoRepository;

    function __construct(EntityManager $em, FormFactoryInterface $formFactory, $manoDeObraRepository, $presupuestoRepository)
    {
        $this->em = $em;
        $this->formFactory = $formFactory;
        $this->manoDeObraRepository = $manoDeObraRepository;
        $this->presupuestoRepository = $presupuestoRepository;
    }

    public function getForm(ManosDeObra $m)
    {
        return $this->formFactory->create(new ManoDeObraForm(), $m);
    }

    /**
     * 
     * @return ManoDeObraRepository
     */
    public function getManoDeObraRepository()
    {
        return $this->em->getRepository($this->manoDeObraRepository);
    }

    public function getAllManosDeObra()
    {
        return $this->getManoDeObraRepository()
                        ->queryAllManosDeObra()
                        ->getArrayResult();
    }

}

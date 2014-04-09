<?php

/*
 * This file is part of the Manuel Aguirre Project.
 *
 * (c) Manuel Aguirre <programador.manuel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace K2\PresupuestoBundle\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Test\FormInterface;

/**
 * Description of AbstractManager
 *
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
abstract class AbstractManager
{

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;
    protected $repositoryName;

    function __construct($repositoryName)
    {
        $this->repositoryName = $repositoryName;
    }

    public function setEm(EntityManager $em)
    {
        $this->em = $em;
    }

    public function setFormFactory(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }
    
    /**
     * @return FormInterface
     */
    public function getForm($object = null)
    {
        return $this->formFactory->create($this->getFormType(), $object);
    }

    /**
     * 
     * @return AbstractType|string
     */
    abstract protected function getFormType();

    /**
     * 
     * @return EntityRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository($this->repositoryName);
    }

}

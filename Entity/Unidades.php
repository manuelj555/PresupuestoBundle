<?php

namespace K2\PresupuestoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Equivalencia
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="UnidadesRepository")
 */
class Unidades
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Materiales", inversedBy="unidades")
     */
    private $material;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Unidad")
     */
    private $unidad;

    /**
     * @var float
     *
     * @ORM\Column(name="factor", type="float")
     */
    private $factor;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isBase", type="boolean", nullable=true)
     */
    private $isBase;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set material
     *
     * @param string $material
     * @return Equivalencia
     */
    public function setMaterial($material)
    {
        $this->material = $material;
    
        return $this;
    }

    /**
     * Get material
     *
     * @return string 
     */
    public function getMaterial()
    {
        return $this->material;
    }

    /**
     * Set unidad
     *
     * @param string $unidad
     * @return Equivalencia
     */
    public function setUnidad($unidad)
    {
        $this->unidad = $unidad;
    
        return $this;
    }

    /**
     * Get unidad
     *
     * @return string 
     */
    public function getUnidad()
    {
        return $this->unidad;
    }

    /**
     * Set factor
     *
     * @param float $factor
     * @return Equivalencia
     */
    public function setFactor($factor)
    {
        $this->factor = $factor;
    
        return $this;
    }

    /**
     * Get factor
     *
     * @return float 
     */
    public function getFactor()
    {
        return $this->factor;
    }

    /**
     * Set isBase
     *
     * @param boolean $isBase
     * @return Equivalencia
     */
    public function setIsBase($isBase)
    {
        $this->isBase = $isBase;
    
        return $this;
    }

    /**
     * Get isBase
     *
     * @return boolean 
     */
    public function getIsBase()
    {
        return $this->isBase;
    }
    
    public function getUnidadName()
    {
        return (string) $this->getUnidad();
    }
}
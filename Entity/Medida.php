<?php

namespace K2\PresupuestoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Medida
 *
 * @ORM\Table(name="medida")
 * @ORM\Entity
 */
class Medida
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="medida", type="string", length=30, nullable=false)
     */
    private $medida;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    private $descripcion;

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
     * Set medida
     *
     * @param string $medida
     * @return Medida
     */
    public function setMedida($medida)
    {
        $this->medida = $medida;
    
        return $this;
    }

    /**
     * Get medida
     *
     * @return string 
     */
    public function getMedida()
    {
        return $this->medida;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Medida
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set fechaAt
     *
     * @param \DateTime $fechaAt
     * @return Medida
     */
    public function setFechaAt($fechaAt)
    {
        $this->fechaAt = $fechaAt;
    
        return $this;
    }

    /**
     * Get fechaAt
     *
     * @return \DateTime 
     */
    public function getFechaAt()
    {
        return $this->fechaAt;
    }

    /**
     * Set fechaIn
     *
     * @param \DateTime $fechaIn
     * @return Medida
     */
    public function setFechaIn($fechaIn)
    {
        $this->fechaIn = $fechaIn;
    
        return $this;
    }

    /**
     * Get fechaIn
     *
     * @return \DateTime 
     */
    public function getFechaIn()
    {
        return $this->fechaIn;
    }
}
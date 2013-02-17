<?php

namespace K2\PresupuestoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Medidas
 *
 * @ORM\Table(name="medidas")
 * @ORM\Entity
 */
class Medidas
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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_at", type="date", nullable=true)
     */
    private $fechaAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_in", type="date", nullable=true)
     */
    private $fechaIn;



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
     * @return Medidas
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
     * @return Medidas
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
     * @return Medidas
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
     * @return Medidas
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
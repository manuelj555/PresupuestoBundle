<?php

namespace K2\PresupuestoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ManosDeObra
 *
 * @ORM\Table(name="manos_de_obra")
 * @ORM\Entity
 */
class ManosDeObra
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
     * @ORM\Column(name="descripcion", type="string", length=300, nullable=false)
     */
    private $descripcion;

    /**
     * @var float
     *
     * @ORM\Column(name="precio", type="float", nullable=false)
     */
    private $precio;

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
     * @var \ManosDeObra
     *
     * @ORM\ManyToOne(targetEntity="ManosDeObra")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipos_de_obras_id", referencedColumnName="id")
     * })
     */
    private $tiposDeObras;

    /**
     * @var \Medidas
     *
     * @ORM\ManyToOne(targetEntity="Medidas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="medidas_id", referencedColumnName="id")
     * })
     */
    private $medidas;

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
     * Set descripcion
     *
     * @param string $descripcion
     * @return ManosDeObra
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
     * Set precio
     *
     * @param float $precio
     * @return ManosDeObra
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return float 
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set fechaAt
     *
     * @param \DateTime $fechaAt
     * @return ManosDeObra
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
     * @return ManosDeObra
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

    /**
     * Set tiposDeObras
     *
     * @param \K2\PresupuestoBundle\Entity\ManosDeObra $tiposDeObras
     * @return ManosDeObra
     */
    public function setTiposDeObras(\K2\PresupuestoBundle\Entity\ManosDeObra $tiposDeObras = null)
    {
        $this->tiposDeObras = $tiposDeObras;

        return $this;
    }

    /**
     * Get tiposDeObras
     *
     * @return \K2\PresupuestoBundle\Entity\ManosDeObra 
     */
    public function getTiposDeObras()
    {
        return $this->tiposDeObras;
    }

    /**
     * Set medidas
     *
     * @param \K2\PresupuestoBundle\Entity\Medidas $medidas
     * @return ManosDeObra
     */
    public function setMedidas(\K2\PresupuestoBundle\Entity\Medidas $medidas = null)
    {
        $this->medidas = $medidas;

        return $this;
    }

    /**
     * Get medidas
     *
     * @return \K2\PresupuestoBundle\Entity\Medidas 
     */
    public function getMedidas()
    {
        return $this->medidas;
    }

}
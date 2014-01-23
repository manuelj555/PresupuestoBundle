<?php

namespace K2\PresupuestoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\VirtualProperty;

/**
 * ManosDeObra
 *
 * @Assert\Callback(methods={"esPrecioFloat"})
 * @ORM\Table(name="manos_de_obra")
 * @ORM\Entity(repositoryClass="ManoDeObraRepository")
 * 
 * @ExclusionPolicy("all")
 */
class ManosDeObra {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * 
     * @Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=300, nullable=false)
     * @Assert\NotBlank(message="La descripción no puede estar vacía")
     * 
     * @Expose
     */
    private $descripcion;

    /**
     * @var float
     *
     * @ORM\Column(name="precio", type="float", nullable=false)
     * @Assert\NotBlank(message="El Precio no puede estar vacío")
     * @ Assert\Type(type="float", message="El precio debe ser un número")
     * 
     * @Expose
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
     * @var TiposDeObras
     *
     * @ORM\ManyToOne(targetEntity="TiposDeObras")
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

    function __construct() {
        $this->fechaAt = $this->fechaIn = new \DateTime('now');
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return ManosDeObra
     */
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion() {
        return $this->descripcion;
    }

    /**
     * Set precio
     *
     * @param float $precio
     * @return ManosDeObra
     */
    public function setPrecio($precio) {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return float 
     */
    public function getPrecio() {
        return $this->precio;
    }

    /**
     * Set fechaAt
     *
     * @param \DateTime $fechaAt
     * @return ManosDeObra
     */
    public function setFechaAt($fechaAt) {
        $this->fechaAt = $fechaAt;

        return $this;
    }

    /**
     * Get fechaAt
     *
     * @return \DateTime 
     */
    public function getFechaAt() {
        return $this->fechaAt;
    }

    /**
     * Set fechaIn
     *
     * @param \DateTime $fechaIn
     * @return ManosDeObra
     */
    public function setFechaIn($fechaIn) {
        $this->fechaIn = $fechaIn;

        return $this;
    }

    /**
     * Get fechaIn
     *
     * @return \DateTime 
     */
    public function getFechaIn() {
        return $this->fechaIn;
    }

    /**
     * 
     * @return TiposDeObras
     */
    public function getTiposDeObras() {
        return $this->tiposDeObras;
    }

    /**
     * 
     * @param \K2\PresupuestoBundle\Entity\TiposDeObras $tiposDeObras
     */
    public function setTiposDeObras(TiposDeObras $tiposDeObras) {
        $this->tiposDeObras = $tiposDeObras;

        return $this;
    }

    /**
     * Set medidas
     *
     * @param \K2\PresupuestoBundle\Entity\Medidas $medidas
     * @return ManosDeObra
     */
    public function setMedidas(\K2\PresupuestoBundle\Entity\Medidas $medidas = null) {
        $this->medidas = $medidas;

        return $this;
    }

    /**
     * Get medidas
     *
     * @return \K2\PresupuestoBundle\Entity\Medidas 
     */
    public function getMedidas() {
        return $this->medidas;
    }

    public function esPrecioFloat(ExecutionContext $context) {
        if (!is_numeric($this->getPrecio())) {
            $context->addViolationAtPath("precio", "El precio debe ser un número válido");
        }
    }
    
    /**
     * 
     * @VirtualProperty
     */
    public function getMedidaName()
    {
        return $this->medidas->getDescripcion();
    }

}
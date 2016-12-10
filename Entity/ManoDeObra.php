<?php

namespace K2\PresupuestoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;

/**
 * ManoDeObra
 *
 * @Assert\Callback(methods={"esPrecioFloat"})
 * @ORM\Table(name="mano_de_obra")
 * @ORM\Entity(repositoryClass="ManoDeObraRepository")
 */
class ManoDeObra {

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
     * @Assert\NotBlank(message="La descripción no puede estar vacía")
     */
    private $descripcion;

    /**
     * @var float
     *
     * @ORM\Column(name="precio", type="float", nullable=false)
     * @Assert\NotBlank(message="El Precio no puede estar vacío")
     * @ Assert\Type(type="float", message="El precio debe ser un número")
     */
    private $precio;

    /**
     * @var TipoDeObra
     *
     * @ORM\ManyToOne(targetEntity="K2\PresupuestoBundle\Entity\TipoDeObra")
     */
    private $tipoDeObra;

    /**
     * @var \Medidas
     *
     * @ORM\ManyToOne(targetEntity="K2\PresupuestoBundle\Entity\Medida")
     */
    private $medida;

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
     * @return ManoDeObra
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
     * @return ManoDeObra
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
     * @return ManoDeObra
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
     * @return ManoDeObra
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
     * @return TipoDeObra
     */
    public function getTiposDeObras() {
        return $this->tiposDeObras;
    }

    /**
     * 
     * @param \K2\PresupuestoBundle\Entity\TipoDeObra $tiposDeObras
     */
    public function setTiposDeObras(TipoDeObra $tiposDeObras) {
        $this->tiposDeObras = $tiposDeObras;

        return $this;
    }

    /**
     * Set medidas
     *
     * @param \K2\PresupuestoBundle\Entity\Medida $medidas
     * @return ManoDeObra
     */
    public function setMedidas(\K2\PresupuestoBundle\Entity\Medida $medidas = null) {
        $this->medidas = $medidas;

        return $this;
    }

    /**
     * Get medidas
     *
     * @return \K2\PresupuestoBundle\Entity\Medida
     */
    public function getMedidas() {
        return $this->medidas;
    }

    public function esPrecioFloat(ExecutionContext $context) {
        if (!is_numeric($this->getPrecio())) {
            $context->addViolationAtPath("precio", "El precio debe ser un número válido");
        }
    }

}
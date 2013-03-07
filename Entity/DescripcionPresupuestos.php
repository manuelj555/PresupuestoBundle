<?php

namespace K2\PresupuestoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DescripcionPresupuestos
 *
 * @ORM\Table(name="descripcion_presupuestos")
 * @ORM\Entity
 */
class DescripcionPresupuestos
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
     * @var integer
     *
     * @ORM\Column(name="posicion", type="integer", nullable=true)
     */
    private $posicion;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=300, nullable=true)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="cantidad", type="string", length=10, nullable=true)
     */
    private $cantidad;

    /**
     * @var string
     *
     * @ORM\Column(name="precio", type="string", length=30, nullable=true)
     */
    private $precio;

    /**
     * @var string
     *
     * @ORM\Column(name="subtotal", type="string", length=10, nullable=true)
     */
    private $subtotal;

    /**
     * @var \Presupuestos
     *
     * @ORM\ManyToOne(targetEntity="Presupuestos", inversedBy="descripciones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_presupuesto", referencedColumnName="id")
     * })
     */
    private $presupuesto;

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
     * Set posicion
     *
     * @param integer $posicion
     * @return DescripcionPresupuestos
     */
    public function setPosicion($posicion)
    {
        $this->posicion = $posicion;

        return $this;
    }

    /**
     * Get posicion
     *
     * @return integer 
     */
    public function getPosicion()
    {
        return $this->posicion;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return DescripcionPresupuestos
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
     * Set cantidad
     *
     * @param string $cantidad
     * @return DescripcionPresupuestos
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return string 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set precio
     *
     * @param string $precio
     * @return DescripcionPresupuestos
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return string 
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set subtotal
     *
     * @param string $subtotal
     * @return DescripcionPresupuestos
     */
    public function setSubtotal($subtotal)
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    /**
     * Get subtotal
     *
     * @return string 
     */
    public function getSubtotal()
    {
        return $this->subtotal;
    }

    /**
     * Set idPresupuesto
     *
     * @param \K2\PresupuestoBundle\Entity\Presupuestos $idPresupuesto
     * @return DescripcionPresupuestos
     */
    public function setPresupuesto(\K2\PresupuestoBundle\Entity\Presupuestos $presupuesto = null)
    {
        $this->presupuesto = $presupuesto;

        return $this;
    }

    /**
     * Get idPresupuesto
     *
     * @return \K2\PresupuestoBundle\Entity\Presupuestos 
     */
    public function getPresupuesto()
    {
        return $this->presupuesto;
    }

    public function calculateSubtotal()
    {
        $this->setSubtotal((float) $this->getPrecio() * (float) $this->getCantidad());
    }

    public function getPrecioNum()
    {
        return (float) $this->precio;
    }

    public function getUnidadMedida()
    {
        return preg_replace('/\d+/', '', $this->precio);
    }

}
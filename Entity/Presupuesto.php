<?php

namespace K2\PresupuestoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use K2\PresupuestoBundle\Entity\DescripcionPresupuesto;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;

/**
 * Presupuesto
 *
 * @ORM\Table(name="presupuesto")
 * @ORM\Entity(repositoryClass="PresupuestoRepository")
 */
class Presupuesto
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
     * @ORM\Column(name="titulo", type="string", length=100, nullable=true)
     */
    private $titulo;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float", nullable=true)
     */
    private $total;

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
     *
     * @var
     * @ORM\OneToMany(targetEntity="K2\PresupuestoBundle\Entity\DescripcionPresupuesto", mappedBy="presupuesto", cascade={"persist", "remove"})
     * @ORM\OrderBy({"posicion":"ASC"})
     */
    private $descripciones;

    function __construct()
    {
        $this->descripciones = new ArrayCollection();
    }

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
     * Set titulo
     *
     * @param string $titulo
     * @return Presupuesto
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set total
     *
     * @param float $total
     * @return Presupuesto
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float 
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set fechaAt
     *
     * @param \DateTime $fechaAt
     * @return Presupuesto
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
     * @return Presupuesto
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
     * 
     * @return ArrayCollection
     */
    public function getDescripciones()
    {
        return $this->descripciones;
    }

    public function setDescripciones($descripciones)
    {
        $this->descripciones = $descripciones;
        foreach ($descripciones as $des) {
            $des->setPresupuesto($this);
        }
    }

    public function guardar(EntityManager $em, $descripcionesOriginales)
    {
        $total = 0;

        foreach ($this->getDescripciones() as $des) {
            $des->calculateSubtotal();
            $total += $des->getSubtotal();
            foreach ($descripcionesOriginales as $key => $actual) {
                if ($actual->getId() === $des->getId()) {
                    //si la descripcion que viene del form está ya está persistida,
                    //la quito de las que se eliminarán de la bd
                    unset($descripcionesOriginales[$key]);
                }
            }
        }

        //eliminamos las originales que no se enviaron en el form
        foreach ($descripcionesOriginales as $des) {
            $des->setPresupuesto(null);
        }

        $this->setTotal($total);
        $em->persist($this);
    }

}
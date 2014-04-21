<?php

namespace K2\PresupuestoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use K2\PresupuestoBundle\Entity\DescripcionPresupuestos;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\VirtualProperty;

/**
 * Presupuestos
 * 
 * @ExclusionPolicy("all")
 *
 * @ORM\Table(name="presupuestos")
 * @ORM\Entity(repositoryClass="PresupuestoRepository")
 */
class Presupuestos
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=100, nullable=true)
     * @Expose
     */
    private $titulo;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float", nullable=true)
     * @Expose
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
     * @ignoreAnnotation("ORM")
     * @ORM\OneToMany(targetEntity="DescripcionPresupuestos", mappedBy="presupuesto", cascade={"persist", "remove"}, orphanRemoval=true) 
     * @ORM\OrderBy({"posicion":"ASC"})
     * 
     * @Expose
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
     * @return Presupuestos
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
     * @return Presupuestos
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
     * @return Presupuestos
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
     * @return Presupuestos
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

    /**
     * Add descripciones
     *
     * @param \K2\PresupuestoBundle\Entity\DescripcionPresupuestos $descripciones
     * @return Presupuestos
     */
    public function addDescripcione(\K2\PresupuestoBundle\Entity\DescripcionPresupuestos $descripciones)
    {
        $this->descripciones[] = $descripciones;
        
        $descripciones->setPresupuesto($this);
    
        return $this;
    }

    /**
     * Remove descripciones
     *
     * @param \K2\PresupuestoBundle\Entity\DescripcionPresupuestos $descripciones
     */
    public function removeDescripcione(\K2\PresupuestoBundle\Entity\DescripcionPresupuestos $descripciones)
    {
        $this->descripciones->removeElement($descripciones);
        
        $descripciones->setPresupuesto(null);
    }
}
<?php

namespace K2\PresupuestoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use K2\PresupuestoBundle\Util\StringUtil;

/**
 * Materiales
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Materiales
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
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="identificador", type="string", length=255)
     */
    private $identificador;

    /**
     * @var string
     *
     * @ORM\Column(name="precio", type="float")
     */
    private $precio;

    /**
     *
     * @ORM\OneToMany(targetEntity="Unidades", mappedBy="material", orphanRemoval=true)
     */
    private $unidades;

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
     * Set nombre
     *
     * @param string $nombre
     * @return Materiales
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        $this->setIdentificador(StringUtil::createIdentifier($nombre));

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set identificador
     *
     * @param string $identificador
     * @return Materiales
     */
    public function setIdentificador($identificador)
    {
        $this->identificador = $identificador;

        return $this;
    }

    /**
     * Get identificador
     *
     * @return string 
     */
    public function getIdentificador()
    {
        return $this->identificador;
    }

    /**
     * Set precio
     *
     * @param string $precio
     * @return Materiales
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

    public function __toString()
    {
        return $this->getNombre();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->unidades = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add unidades
     *
     * @param \K2\PresupuestoBundle\Entity\Unidades $unidades
     * @return Materiales
     */
    public function addUnidade(\K2\PresupuestoBundle\Entity\Unidades $unidades)
    {
        $this->unidades[] = $unidades;

        $unidades->setMaterial($this);

        return $this;
    }

    /**
     * Remove unidades
     *
     * @param \K2\PresupuestoBundle\Entity\Unidades $unidades
     */
    public function removeUnidade(\K2\PresupuestoBundle\Entity\Unidades $unidades)
    {
        $this->unidades->removeElement($unidades);

        $unidades->setMaterial(null);
    }

    /**
     * Get unidades
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUnidades()
    {
        return $this->unidades;
    }

}

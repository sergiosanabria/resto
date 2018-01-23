<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Base\BaseClass;
use Doctrine\ORM\Mapping as ORM;

/**
 * Mesa
 *
 * @ORM\Table(name="mesa")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MesaRepository")
 */
class Mesa extends BaseClass
{
    /**
     * @var int
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
     * @var int
     *
     * @ORM\Column(name="fila", type="integer", nullable=true)
     */
    private $fila;

    /**
     * @var int
     *
     * @ORM\Column(name="columna", type="integer", nullable=true)
     */
    private $columna;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\SectorMesa", inversedBy="mesas")
     * @ORM\JoinColumn(name="sector_id", referencedColumnName="id")
     */
    private $sector;

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PedidoMesa", mappedBy="mesa")
     */
    private $pedidos;


    public function getEstado()
    {
        if ($this->getPedidos()->isEmpty()){
            return PedidoMesa::$ESTADO_LIBRE;
        }else{
            return $this->getPedidos()->last()->getEstado();
        }
    }
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Mesa
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

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
     * Set fila
     *
     * @param integer $fila
     *
     * @return Mesa
     */
    public function setFila($fila)
    {
        $this->fila = $fila;

        return $this;
    }

    /**
     * Get fila
     *
     * @return int
     */
    public function getFila()
    {
        return $this->fila;
    }

    /**
     * Set columna
     *
     * @param integer $columna
     *
     * @return Mesa
     */
    public function setColumna($columna)
    {
        $this->columna = $columna;

        return $this;
    }

    /**
     * Get columna
     *
     * @return int
     */
    public function getColumna()
    {
        return $this->columna;
    }

    /**
     * Set sector
     *
     * @param \AppBundle\Entity\SectorMesa $sector
     *
     * @return Mesa
     */
    public function setSector(\AppBundle\Entity\SectorMesa $sector = null)
    {
        $this->sector = $sector;

        return $this;
    }

    /**
     * Get sector
     *
     * @return \AppBundle\Entity\SectorMesa
     */
    public function getSector()
    {
        return $this->sector;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return Mesa
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Set fechaActualizacion
     *
     * @param \DateTime $fechaActualizacion
     *
     * @return Mesa
     */
    public function setFechaActualizacion($fechaActualizacion)
    {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    /**
     * Set creadoPor
     *
     * @param \UsuariosBundle\Entity\Usuario $creadoPor
     *
     * @return Mesa
     */
    public function setCreadoPor(\UsuariosBundle\Entity\Usuario $creadoPor = null)
    {
        $this->creadoPor = $creadoPor;

        return $this;
    }

    /**
     * Set actualizadoPor
     *
     * @param \UsuariosBundle\Entity\Usuario $actualizadoPor
     *
     * @return Mesa
     */
    public function setActualizadoPor(\UsuariosBundle\Entity\Usuario $actualizadoPor = null)
    {
        $this->actualizadoPor = $actualizadoPor;

        return $this;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pedidos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add pedido
     *
     * @param \AppBundle\Entity\PedidoMesa $pedido
     *
     * @return Mesa
     */
    public function addPedido(\AppBundle\Entity\PedidoMesa $pedido)
    {
        $this->pedidos[] = $pedido;

        return $this;
    }

    /**
     * Remove pedido
     *
     * @param \AppBundle\Entity\PedidoMesa $pedido
     */
    public function removePedido(\AppBundle\Entity\PedidoMesa $pedido)
    {
        $this->pedidos->removeElement($pedido);
    }

    /**
     * Get pedidos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidos()
    {
        return $this->pedidos;
    }
}

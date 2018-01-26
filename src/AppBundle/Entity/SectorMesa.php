<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Base\BaseClass;
use Doctrine\ORM\Mapping as ORM;

/**
 * SectorMesa
 *
 * @ORM\Table(name="sector_mesa")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SectorMesaRepository")
 */
class SectorMesa extends BaseClass
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
     * @ORM\Column(name="nombre", type="string", length=255, unique=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad_columnas", type="integer", nullable=true)
     */
    private $cantidadColumnas;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad_mesas", type="integer", nullable=true)
     */
    private $cantidadMesas;

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Mesa", mappedBy="sector", cascade={"persist"})
     */
    private $mesas;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Empresa")
     * @ORM\JoinColumn(name="empresa_id", referencedColumnName="id")
     */
    private $empresa;

    public function getMatrixMesa()
    {
        $mesas = $this->getMesas();

        $mesasMatrix = [];

        foreach ($mesas as $mesa) {
            if ($mesa->getActivo()){
                $mesasMatrix[(int)$mesa->getColumna()][(int)$mesa->getFila()] = array(
                    "id" => $mesa->getId(),
                    "estado" => $mesa->getEstado(),
                    "nombre" => $mesa->getNombre(),
                    "creado" => true,

                );
            }
        }

        return $mesasMatrix;
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
     * @return SectorMesa
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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return SectorMesa
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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return SectorMesa
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
     * @return SectorMesa
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
     * @return SectorMesa
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
     * @return SectorMesa
     */
    public function setActualizadoPor(\UsuariosBundle\Entity\Usuario $actualizadoPor = null)
    {
        $this->actualizadoPor = $actualizadoPor;

        return $this;
    }

    /**
     * Set cantidadColumnas
     *
     * @param integer $cantidadColumnas
     *
     * @return SectorMesa
     */
    public function setCantidadColumnas($cantidadColumnas)
    {
        $this->cantidadColumnas = $cantidadColumnas;

        return $this;
    }

    /**
     * Get cantidadColumnas
     *
     * @return integer
     */
    public function getCantidadColumnas()
    {
        return $this->cantidadColumnas;
    }

    /**
     * Set cantidadMesas
     *
     * @param integer $cantidadMesas
     *
     * @return SectorMesa
     */
    public function setCantidadMesas($cantidadMesas)
    {
        $this->cantidadMesas = $cantidadMesas;

        return $this;
    }

    /**
     * Get cantidadMesas
     *
     * @return integer
     */
    public function getCantidadMesas()
    {
        return $this->cantidadMesas;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->mesas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add mesa
     *
     * @param \AppBundle\Entity\Mesa $mesa
     *
     * @return SectorMesa
     */
    public function addMesa(\AppBundle\Entity\Mesa $mesa)
    {
        $this->mesas[] = $mesa;

        return $this;
    }

    /**
     * Remove mesa
     *
     * @param \AppBundle\Entity\Mesa $mesa
     */
    public function removeMesa(\AppBundle\Entity\Mesa $mesa)
    {
        $this->mesas->removeElement($mesa);
    }

    /**
     * Get mesas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMesas()
    {
        return $this->mesas;
    }

    /**
     * Set empresa
     *
     * @param \AppBundle\Entity\Empresa $empresa
     *
     * @return SectorMesa
     */
    public function setEmpresa(\AppBundle\Entity\Empresa $empresa = null)
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return \AppBundle\Entity\Empresa
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }
}

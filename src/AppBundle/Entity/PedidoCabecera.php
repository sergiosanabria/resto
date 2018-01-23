<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Base\BaseClass;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;

/**
 * PedidoCabecera
 *
 * @ORM\Table(name="pedido_cabecera")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PedidoCabeceraRepository")
 * @ExclusionPolicy("all")
 */
class PedidoCabecera extends BaseClass
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose()
     */
    private $id;

    /**
     * @var string
     * @Expose()
     * @ORM\Column(name="observacion", type="text")
     */
    private $observacion;

    /**
     * @var bool
     * @Expose()
     * @ORM\Column(name="delivery", type="boolean", nullable=true)
     */
    private $delivery;

    /**
     * @var string
     * @Expose()
     * @ORM\Column(name="estado", type="string", length=255)
     */
    private $estado;

    /**
     * @var string
     *
     * @ORM\Column(name="motivo_cancelacion_cliente", type="text", nullable=true)
     */
    private $motivoCancelacionCliente;

    /**
     * @var string
     *
     * @ORM\Column(name="motivo_cancelacion_empresa", type="text", nullable=true)
     */
    private $motivoCancelacionEmpresa;

    /**
     * @var string
     *
     * @ORM\Column(name="total", type="decimal", precision=10, scale=2)
     */
    private $total;


    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Empresa")
     * @ORM\JoinColumn(name="empresa_id", referencedColumnName="id")
     * @Exclude()
     */
    private $empresa;


    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Cliente")
     * @ORM\JoinColumn(name="cliente_id", referencedColumnName="id")
     */
    private $cliente;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CostoEnvio")
     * @ORM\JoinColumn(name="costo_envio_id", referencedColumnName="id", nullable=true)
     */
    private $costoEnvio;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Repartidor", inversedBy="pedidos")
     * @ORM\JoinColumn(name="repartidor", referencedColumnName="id", nullable=true)
     * @Expose()
     */
    private $repartidor;

    /**
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PedidoItem", mappedBy="pedidoCabecera", cascade={"persist"})
     */
    private $pedidosItem;

    public static $DELIVERY = "delivery";
    public static $MESA = "mesa";
    public static $LLEVAR = "llevar";


    /**
     * @VirtualProperty()
     * @SerializedName("items")
     */
    public function getItemsActivos()
    {
        $retorno = array();
        foreach ($this->pedidosItem as $item) {
            if ($item->getActivo()) {
                $retorno [] = $item;
            }
        }

        return $retorno;


    }

    /**
     * @VirtualProperty()
     * @SerializedName("creado")
     */
    public function getFechaCreacion()
    {
        return parent::getFechaCreacion();
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
     * Set observacion
     *
     * @param string $observacion
     * @return PedidoCabecera
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;

        return $this;
    }

    /**
     * Get observacion
     *
     * @return string 
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * Set delivery
     *
     * @param boolean $delivery
     * @return PedidoCabecera
     */
    public function setDelivery($delivery)
    {
        $this->delivery = $delivery;

        return $this;
    }

    /**
     * Get delivery
     *
     * @return boolean 
     */
    public function getDelivery()
    {
        return $this->delivery;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return PedidoCabecera
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set motivoCancelacionCliente
     *
     * @param string $motivoCancelacionCliente
     * @return PedidoCabecera
     */
    public function setMotivoCancelacionCliente($motivoCancelacionCliente)
    {
        $this->motivoCancelacionCliente = $motivoCancelacionCliente;

        return $this;
    }

    /**
     * Get motivoCancelacionCliente
     *
     * @return string 
     */
    public function getMotivoCancelacionCliente()
    {
        return $this->motivoCancelacionCliente;
    }

    /**
     * Set motivoCancelacionEmpresa
     *
     * @param string $motivoCancelacionEmpresa
     * @return PedidoCabecera
     */
    public function setMotivoCancelacionEmpresa($motivoCancelacionEmpresa)
    {
        $this->motivoCancelacionEmpresa = $motivoCancelacionEmpresa;

        return $this;
    }

    /**
     * Get motivoCancelacionEmpresa
     *
     * @return string 
     */
    public function getMotivoCancelacionEmpresa()
    {
        return $this->motivoCancelacionEmpresa;
    }

    /**
     * Set total
     *
     * @param string $total
     * @return PedidoCabecera
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return string 
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return PedidoCabecera
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
     * @return PedidoCabecera
     */
    public function setFechaActualizacion($fechaActualizacion)
    {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    /**
     * Set empresa
     *
     * @param \AppBundle\Entity\Empresa $empresa
     * @return PedidoCabecera
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

    /**
     * Set cliente
     *
     * @param \AppBundle\Entity\Cliente $cliente
     * @return PedidoCabecera
     */
    public function setCliente(\AppBundle\Entity\Cliente $cliente = null)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente
     *
     * @return \AppBundle\Entity\Cliente 
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Set creadoPor
     *
     * @param \UsuariosBundle\Entity\Usuario $creadoPor
     * @return PedidoCabecera
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
     * @return PedidoCabecera
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
        $this->pedidosItem = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add pedidosItem
     *
     * @param \AppBundle\Entity\PedidoItem $pedidosItem
     *
     * @return PedidoCabecera
     */
    public function addPedidosItem(\AppBundle\Entity\PedidoItem $pedidosItem)
    {
        $this->pedidosItem[] = $pedidosItem;

        return $this;
    }

    /**
     * Remove pedidosItem
     *
     * @param \AppBundle\Entity\PedidoItem $pedidosItem
     */
    public function removePedidosItem(\AppBundle\Entity\PedidoItem $pedidosItem)
    {
        $this->pedidosItem->removeElement($pedidosItem);
    }

    /**
     * Get pedidosItem
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosItem()
    {
        return $this->pedidosItem;
    }




    /**
     * Set repartidor
     *
     * @param \AppBundle\Entity\Repartidor $repartidor
     *
     * @return PedidoCabecera
     */
    public function setRepartidor(\AppBundle\Entity\Repartidor $repartidor = null)
    {
        $this->repartidor = $repartidor;

        return $this;
    }

    /**
     * Get repartidor
     *
     * @return \AppBundle\Entity\Repartidor
     */
    public function getRepartidor()
    {
        return $this->repartidor;
    }

    /**
     * Set costoEnvio
     *
     * @param \AppBundle\Entity\CostoEnvio $costoEnvio
     *
     * @return PedidoCabecera
     */
    public function setCostoEnvio(\AppBundle\Entity\CostoEnvio $costoEnvio = null)
    {
        $this->costoEnvio = $costoEnvio;

        return $this;
    }

    /**
     * Get costoEnvio
     *
     * @return \AppBundle\Entity\CostoEnvio
     */
    public function getCostoEnvio()
    {
        return $this->costoEnvio;
    }
}

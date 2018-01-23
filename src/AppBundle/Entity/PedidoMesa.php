<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Base\BaseClass;
use Doctrine\ORM\Mapping as ORM;

/**
 * ClienteEmpresa
 *
 * @ORM\Table(name="pedido_mesa")
 * @ORM\Entity()
 */
class PedidoMesa extends BaseClass
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
     * @var
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PedidoCabecera")
     * @ORM\JoinColumn(name="pedido_id", referencedColumnName="id")
     */
    private $pedido;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Mesa", inversedBy="pedidos")
     * @ORM\JoinColumn(name="mesa_id", referencedColumnName="id")
     */
    private $mesa;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=50, nullable=false)
     */
    private $estado;


    public static $ESTADO_LIBRE = "libre";
    public static $ESTADO_OCUPADO = "ocupado";
    public static $ESTADO_RESERVADO = "reservado";

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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return PedidoMesa
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
     * @return PedidoMesa
     */
    public function setFechaActualizacion($fechaActualizacion)
    {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    /**
     * Set pedido
     *
     * @param \AppBundle\Entity\PedidoCabecera $pedido
     *
     * @return PedidoMesa
     */
    public function setPedido(\AppBundle\Entity\PedidoCabecera $pedido = null)
    {
        $this->pedido = $pedido;

        return $this;
    }

    /**
     * Get pedido
     *
     * @return \AppBundle\Entity\PedidoCabecera
     */
    public function getPedido()
    {
        return $this->pedido;
    }

    /**
     * Set mesa
     *
     * @param \AppBundle\Entity\Mesa $mesa
     *
     * @return PedidoMesa
     */
    public function setMesa(\AppBundle\Entity\Mesa $mesa = null)
    {
        $this->mesa = $mesa;

        return $this;
    }

    /**
     * Get mesa
     *
     * @return \AppBundle\Entity\Mesa
     */
    public function getMesa()
    {
        return $this->mesa;
    }

    /**
     * Set creadoPor
     *
     * @param \UsuariosBundle\Entity\Usuario $creadoPor
     *
     * @return PedidoMesa
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
     * @return PedidoMesa
     */
    public function setActualizadoPor(\UsuariosBundle\Entity\Usuario $actualizadoPor = null)
    {
        $this->actualizadoPor = $actualizadoPor;

        return $this;
    }

    /**
     * Set estado
     *
     * @param string $estado
     *
     * @return PedidoMesa
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
}

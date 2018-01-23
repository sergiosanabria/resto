<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Base\BaseClass;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;

/**
 * PedidoItem
 *
 * @ORM\Table(name="pedido_items")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PedidoItemRepository")
 */
class PedidoItem extends BaseClass
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
     * @ORM\Column(name="precio", type="decimal", precision=10, scale=2)
     */
    private $precio;

    /**
     * @var string
     *
     * @ORM\Column(name="cantidad", type="decimal", precision=10, scale=2)
     */
    private $cantidad;

    /**
     * @var string
     *
     * @ORM\Column(name="total", type="decimal", precision=10, scale=2)
     */
    private $total;

    /**
     * @var string
     *
     * @ORM\Column(name="detalles", type="text", nullable=true)
     */
    private $detalles;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Producto")
     * @ORM\JoinColumn(name="producto_id", referencedColumnName="id")
     */
    private $producto;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Combo")
     * @ORM\JoinColumn(name="combo_id", referencedColumnName="id")
     */
    private $combo;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PedidoCabecera", inversedBy="pedidosItem")
     * @ORM\JoinColumn(name="pedido_cabecera_id", referencedColumnName="id")
     */
    private $pedidoCabecera;


    /**
     * Calcula el total del item haciendo el producto del precio x la cantidad
     *
     * @param bool $setTotal Setea el attr total
     */
    public function calcularTotal($setTotal = true)
    {
        $total = $this->getCantidad() * $this->getPrecio();

        if ($setTotal) {
            $this->setTotal($total);
        }

        return $total;
    }


    /**
     * @VirtualProperty()
     * @SerializedName("activo")
     */
    public function getActivo()
    {
        return parent::getActivo();
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
     * Set precio
     *
     * @param string $precio
     * @return PedidoItem
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
     * Set cantidad
     *
     * @param string $cantidad
     * @return PedidoItem
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
     * Set total
     *
     * @param string $total
     * @return PedidoItem
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
     * Set detalles
     *
     * @param string $detalles
     * @return PedidoItem
     */
    public function setDetalles($detalles)
    {
        $this->detalles = $detalles;

        return $this;
    }

    /**
     * Get detalles
     *
     * @return string
     */
    public function getDetalles()
    {
        return $this->detalles;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return PedidoItem
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
     * @return PedidoItem
     */
    public function setFechaActualizacion($fechaActualizacion)
    {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    /**
     * Set producto
     *
     * @param \AppBundle\Entity\Producto $producto
     * @return PedidoItem
     */
    public function setProducto(\AppBundle\Entity\Producto $producto = null)
    {
        $this->producto = $producto;

        return $this;
    }

    /**
     * Get producto
     *
     * @return \AppBundle\Entity\Producto
     */
    public function getProducto()
    {
        return $this->producto;
    }

    /**
     * Set combo
     *
     * @param \AppBundle\Entity\Combo $combo
     * @return PedidoItem
     */
    public function setCombo(\AppBundle\Entity\Combo $combo = null)
    {
        $this->combo = $combo;

        return $this;
    }

    /**
     * Get combo
     *
     * @return \AppBundle\Entity\Combo
     */
    public function getCombo()
    {
        return $this->combo;
    }

    /**
     * Set pedidoCabecera
     *
     * @param \AppBundle\Entity\PedidoCabecera $pedidoCabecera
     * @return PedidoItem
     */
    public function setPedidoCabecera(\AppBundle\Entity\PedidoCabecera $pedidoCabecera = null)
    {
        $this->pedidoCabecera = $pedidoCabecera;

        return $this;
    }

    /**
     * Get pedidoCabecera
     *
     * @return \AppBundle\Entity\PedidoCabecera
     */
    public function getPedidoCabecera()
    {
        return $this->pedidoCabecera;
    }

    /**
     * Set creadoPor
     *
     * @param \UsuariosBundle\Entity\Usuario $creadoPor
     * @return PedidoItem
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
     * @return PedidoItem
     */
    public function setActualizadoPor(\UsuariosBundle\Entity\Usuario $actualizadoPor = null)
    {
        $this->actualizadoPor = $actualizadoPor;

        return $this;
    }
}

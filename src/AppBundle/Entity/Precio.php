<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Base\BaseClass;
use Doctrine\ORM\Mapping as ORM;

/**
 * Precio
 *
 * @ORM\Table(name="precio")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PrecioRepository")
 */
class Precio extends BaseClass
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
     * @ORM\Column(name="tipo_precio", type="string", length=30)
     */
    private $tipoPrecio;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Producto", inversedBy="precios")
     * @ORM\JoinColumn(name="producto_id", referencedColumnName="id")
     */
    private $producto;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Combo", inversedBy="precios")
     * @ORM\JoinColumn(name="combo_id", referencedColumnName="id")
     */
    private $combo;

    public static $tipoPrecioVenta = "precio-venta";
    public static $tipoPrecioCosto = "precio-costo";


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
     * Set precio
     *
     * @param string $precio
     *
     * @return Precio
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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return Precio
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
     * @return Precio
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
     *
     * @return Precio
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
     *
     * @return Precio
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
     * Set creadoPor
     *
     * @param \UsuariosBundle\Entity\Usuario $creadoPor
     *
     * @return Precio
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
     * @return Precio
     */
    public function setActualizadoPor(\UsuariosBundle\Entity\Usuario $actualizadoPor = null)
    {
        $this->actualizadoPor = $actualizadoPor;

        return $this;
    }

    /**
     * Set tipoPrecio
     *
     * @param string $tipoPrecio
     *
     * @return Precio
     */
    public function setTipoPrecio($tipoPrecio)
    {
        $this->tipoPrecio = $tipoPrecio;

        return $this;
    }

    /**
     * Get tipoPrecio
     *
     * @return string
     */
    public function getTipoPrecio()
    {
        return $this->tipoPrecio;
    }
}

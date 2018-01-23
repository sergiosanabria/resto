<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Base\BaseClass;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Producto
 *
 * @ORM\Table(name="productos")
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductoRepository")
 */
class Producto extends BaseClass
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
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=true)
     */
    private $descripcion;


    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProductoIngrediente", mappedBy="producto", cascade={"persist"})
     */
    private $ingredientes;

    /**
     * @var bool
     *
     * @ORM\Column(name="controla_stock", type="boolean", nullable=true)
     */
    private $controlaStock;

    /**
     * @var string
     *
     * @ORM\Column(name="cantidad", type="decimal", precision=10, scale=2)
     */
    private $cantidad;

    /**
     * @var string
     *
     * @ORM\Column(name="precio_de_venta", nullable=true, type="decimal", precision=10, scale=2)
     */
    private $precioDeVenta;

    /**
     * @var string
     *
     * @ORM\Column(name="precio_de_costo", nullable=true, type="decimal", precision=10, scale=2)
     */
    private $precioDeCosto;

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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Categoria", inversedBy="productos")
     * @ORM\JoinColumn(name="categoria_id", referencedColumnName="id")
     * @Exclude()
     */
    private $categoria;

    /**
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\DescuentoProducto", mappedBy="producto")
     */
    private $descuentos;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="image_producto", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Precio", mappedBy="producto", cascade={"persist"})
     */
    private $precios;


    /**
     * @VirtualProperty()
     * @SerializedName("iscombo")
     */
    public function isCombo()
    {
        return false;
    }

    /**
     * @VirtualProperty()
     * @SerializedName("isproducto")
     */
    public function isProducto()
    {
        return true;
    }

    // ...

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function __toString()
    {
        return $this->nombre;
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
     * Set nombre
     *
     * @param string $nombre
     * @return Producto
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
     * @return Producto
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
     * Set controlaStock
     *
     * @param boolean $controlaStock
     * @return Producto
     */
    public function setControlaStock($controlaStock)
    {
        $this->controlaStock = $controlaStock;

        return $this;
    }

    /**
     * Get controlaStock
     *
     * @return boolean
     */
    public function getControlaStock()
    {
        return $this->controlaStock;
    }

    /**
     * Set cantidad
     *
     * @param string $cantidad
     * @return Producto
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
     * Set precioDeVenta
     *
     * @param string $precioDeVenta
     * @return Producto
     */
    public function setPrecioDeVenta($precioDeVenta)
    {
        $this->precioDeVenta = $precioDeVenta;

        return $this;
    }

    /**
     * Get precioDeVenta
     *
     * @return string
     */
    public function getPrecioDeVenta()
    {
        return $this->precioDeVenta;
    }

    /**
     * Set precioDeCosto
     *
     * @param string $precioDeCosto
     * @return Producto
     */
    public function setPrecioDeCosto($precioDeCosto)
    {
        $this->precioDeCosto = $precioDeCosto;

        return $this;
    }

    /**
     * Get precioDeCosto
     *
     * @return string
     */
    public function getPrecioDeCosto()
    {
        return $this->precioDeCosto;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return Producto
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
     * @return Producto
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
     * @return Producto
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
     * Set categoria
     *
     * @param \AppBundle\Entity\Categoria $categoria
     * @return Producto
     */
    public function setCategoria(\AppBundle\Entity\Categoria $categoria = null)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return \AppBundle\Entity\Categoria
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Set creadoPor
     *
     * @param \UsuariosBundle\Entity\Usuario $creadoPor
     * @return Producto
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
     * @return Producto
     */
    public function setActualizadoPor(\UsuariosBundle\Entity\Usuario $actualizadoPor = null)
    {
        $this->actualizadoPor = $actualizadoPor;

        return $this;
    }

    /**
     * Set ingredientes
     *
     * @param string $ingredientes
     *
     * @return Producto
     */
    public function setIngredientes($ingredientes)
    {
        $this->ingredientes = $ingredientes;

        return $this;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->descuentos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add descuento
     *
     * @param \AppBundle\Entity\DescuentoProducto $descuento
     *
     * @return Producto
     */
    public function addDescuento(\AppBundle\Entity\DescuentoProducto $descuento)
    {
        $this->descuentos[] = $descuento;

        return $this;
    }

    /**
     * Remove descuento
     *
     * @param \AppBundle\Entity\DescuentoProducto $descuento
     */
    public function removeDescuento(\AppBundle\Entity\DescuentoProducto $descuento)
    {
        $this->descuentos->removeElement($descuento);
    }

    /**
     * Get descuentos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDescuentos()
    {
        return $this->descuentos;
    }

    /**
     * Get ingredientes
     *
     * @return string
     */
    public function getIngredientes()
    {
        $array = new ArrayCollection();
        if ($this->ingredientes && count($this->ingredientes)) {
            foreach ($this->ingredientes as $ingrediente) {
                if ($ingrediente->getActivo()) {
                    $array->add($ingrediente);
                }
            }
        }

        return $array;
    }

    /**
     * Get ingredientes
     *
     * @return string
     */
    public function getIngredientesSinRelacion()
    {
        $array = new ArrayCollection();
        if ($this->ingredientes && count($this->ingredientes)) {
            foreach ($this->ingredientes as $ingrediente) {
                if ($ingrediente->getActivo()) {
                    $array->add($ingrediente->getIngrediente());
                }
            }
        }

        return $array;
    }


    public function getStringIngredientes()
    {

        $array = [];

        foreach ($this->getIngredientes() as $ingrediente) {
            $array [] = $ingrediente->getIngrediente()->getNombre();
        }

        return $array;

    }

    /**
     * Add ingrediente
     *
     * @param \AppBundle\Entity\ProductoIngrediente $ingrediente
     *
     * @return Producto
     */
    public function addIngrediente(\AppBundle\Entity\ProductoIngrediente &$ingrediente)
    {

        $ingrediente->setProducto($this);

        $ingrediente->setActivo(true);

        $this->ingredientes[] = $ingrediente;

        return $this;
    }

    /**
     * Remove ingrediente
     *
     * @param \AppBundle\Entity\ProductoIngrediente $ingrediente
     */
    public function removeIngrediente(\AppBundle\Entity\ProductoIngrediente &$ingrediente)
    {
        $ingrediente->setActivo(false);

        $this->ingredientes->removeElement($ingrediente);
    }

    /**
     * Add precio
     *
     * @param \AppBundle\Entity\Precio $precio
     *
     * @return Producto
     */
    public function addPrecio(\AppBundle\Entity\Precio $precio)
    {
        $this->precios[] = $precio;

        return $this;
    }

    /**
     * Remove precio
     *
     * @param \AppBundle\Entity\Precio $precio
     */
    public function removePrecio(\AppBundle\Entity\Precio $precio)
    {
        $this->precios->removeElement($precio);
    }

    /**
     * Get precios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrecios()
    {
        return $this->precios;
    }
}

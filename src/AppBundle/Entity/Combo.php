<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Base\BaseClass;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Combo
 *
 * @ORM\Table(name="combos")
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ComboRepository")
 * @ExclusionPolicy("all")
 */
class Combo extends BaseClass {
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
	 *
	 * @ORM\Column(name="nombre", type="string", length=255)
	 * @Expose()
	 */
	private $nombre;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="descripcion", type="string", length=255, nullable=true)
	 * @Expose()
	 */
	private $descripcion;

	/**
	 * @var string
	 * @Expose()
	 * @ORM\Column(name="porcentaje", type="decimal", precision=10, scale=2, nullable=true)
	 */
	private $porcentaje;

    /**
     * @var string
     *
     * @ORM\Column(name="precio_de_venta", type="decimal", precision=10, scale=2)
     */
    private $precioDeVenta;

    /**
     * @var string
     *
     * @ORM\Column(name="precio_de_costo", type="decimal", precision=10, scale=2)
     */
    private $precioDeCosto;

	/**
	 * @var
	 *
	 * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProductoCombo", mappedBy="combo",cascade={"persist", "remove"})
	 */
	private $productos;


	/**
	 * @var
	 *
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Empresa")
	 * @ORM\JoinColumn(name="empresa_id", referencedColumnName="id")
	 */
	private $empresa;

	/**
	 * @var
	 *
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Categoria")
	 * @ORM\JoinColumn(name="categoria_id", referencedColumnName="id")
	 */
	private $categoria;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 * @var string
	 */
	private $image;

	/**
	 * @Vich\UploadableField(mapping="combo_images", fileNameProperty="image")
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
	public function isCombo (){
		return true;
	}

	/**
	 * @VirtualProperty()
	 * @SerializedName("isproducto")
	 */
	public function isProducto (){
		return false;
	}

	/**
	 * @VirtualProperty()
	 * @SerializedName("productos")
	 */
	public function getProductosToString (){
		$productos = array();
		foreach ($this->getProductos() as $producto) {
			$productos [] = $producto->getProducto()->getNombre();
		}

		return implode(', ', $productos);
	}

	// ...

	public function setImageFile( File $image = null ) {
		$this->imageFile = $image;

		// VERY IMPORTANT:
		// It is required that at least one field changes if you are using Doctrine,
		// otherwise the event listeners won't be called and the file is lost
		if ( $image ) {
			// if 'updatedAt' is not defined in your entity, use another property
			$this->updatedAt = new \DateTime( 'now' );
		}
	}

	public function getImageFile() {
		return $this->imageFile;
	}

	public function setImage( $image ) {
		$this->image = $image;
	}

	public function getImage() {
		return $this->image;
	}


	/**
	 * @VirtualProperty()
	 * @SerializedName("precio_de_venta")
	 */
//	public function getPrecioVenta() {
//		$precioVenta = 0;
//
//		if ( $this->getPrecio() ) {
//			$precioVenta = $this->getPrecio();
//		}
//
//		foreach ( $this->getProductos() as $producto ) {
//			$precioVenta += $producto->getProducto()->getPrecioDeVenta();
//		}
//
//		if ( $this->getPorcentaje() ) {
//			$porcentaje  = $this->getPorcentaje();
//			$precioVenta = $precioVenta - ( ( $precioVenta * $porcentaje ) / 100 );
//		}
//
//		return $precioVenta;
//	}


	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set nombre
	 *
	 * @param string $nombre
	 *
	 * @return Combo
	 */
	public function setNombre( $nombre ) {
		$this->nombre = $nombre;

		return $this;
	}

	/**
	 * Get nombre
	 *
	 * @return string
	 */
	public function getNombre() {
		return $this->nombre;
	}

	/**
	 * Set descripcion
	 *
	 * @param string $descripcion
	 *
	 * @return Combo
	 */
	public function setDescripcion( $descripcion ) {
		$this->descripcion = $descripcion;

		return $this;
	}

	/**
	 * Get descripcion
	 *
	 * @return string
	 */
	public function getDescripcion() {
		return $this->descripcion;
	}

	/**
	 * Set porcentaje
	 *
	 * @param string $porcentaje
	 *
	 * @return Combo
	 */
	public function setPorcentaje( $porcentaje ) {
		$this->porcentaje = $porcentaje;

		return $this;
	}

	/**
	 * Get porcentaje
	 *
	 * @return string
	 */
	public function getPorcentaje() {
		return $this->porcentaje;
	}

	/**


	/**
	 * Constructor
	 */
	public function __construct() {
		$this->productos = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * Set fechaCreacion
	 *
	 * @param \DateTime $fechaCreacion
	 *
	 * @return Combo
	 */
	public function setFechaCreacion( $fechaCreacion ) {
		$this->fechaCreacion = $fechaCreacion;

		return $this;
	}

	/**
	 * Set fechaActualizacion
	 *
	 * @param \DateTime $fechaActualizacion
	 *
	 * @return Combo
	 */
	public function setFechaActualizacion( $fechaActualizacion ) {
		$this->fechaActualizacion = $fechaActualizacion;

		return $this;
	}

	/**
	 * Add productos
	 *
	 * @param \AppBundle\Entity\ProductoCombo $productos
	 *
	 * @return Combo
	 */
	public function addProducto( \AppBundle\Entity\ProductoCombo $productos ) {
		$this->productos[] = $productos;

		return $this;
	}

	/**
	 * Remove productos
	 *
	 * @param \AppBundle\Entity\ProductoCombo $productos
	 */
	public function removeProducto( \AppBundle\Entity\ProductoCombo $productos ) {
		$this->productos->removeElement( $productos );
	}

	/**
	 * Get productos
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getProductos() {
		return $this->productos;
	}

	/**
	 * Set empresa
	 *
	 * @param \AppBundle\Entity\Empresa $empresa
	 *
	 * @return Combo
	 */
	public function setEmpresa( \AppBundle\Entity\Empresa $empresa = null ) {
		$this->empresa = $empresa;

		return $this;
	}

	/**
	 * Get empresa
	 *
	 * @return \AppBundle\Entity\Empresa
	 */
	public function getEmpresa() {
		return $this->empresa;
	}

	/**
	 * Set categoria
	 *
	 * @param \AppBundle\Entity\Categoria $categoria
	 *
	 * @return Combo
	 */
	public function setCategoria( \AppBundle\Entity\Categoria $categoria = null ) {
		$this->categoria = $categoria;

		return $this;
	}

	/**
	 * Get categoria
	 *
	 * @return \AppBundle\Entity\Categoria
	 */
	public function getCategoria() {
		return $this->categoria;
	}

	/**
	 * Set creadoPor
	 *
	 * @param \UsuariosBundle\Entity\Usuario $creadoPor
	 *
	 * @return Combo
	 */
	public function setCreadoPor( \UsuariosBundle\Entity\Usuario $creadoPor = null ) {
		$this->creadoPor = $creadoPor;

		return $this;
	}

	/**
	 * Set actualizadoPor
	 *
	 * @param \UsuariosBundle\Entity\Usuario $actualizadoPor
	 *
	 * @return Combo
	 */
	public function setActualizadoPor( \UsuariosBundle\Entity\Usuario $actualizadoPor = null ) {
		$this->actualizadoPor = $actualizadoPor;

		return $this;
	}

    /**
     * Add precio
     *
     * @param \AppBundle\Entity\Precio $precio
     *
     * @return Combo
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

    /**
     * Set precioDeVenta
     *
     * @param string $precioDeVenta
     *
     * @return Combo
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
     *
     * @return Combo
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
}

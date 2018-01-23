<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Base\BaseClass;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Empresa
 *
 * @Vich\Uploadable
 * @ORM\Table(name="empresas")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EmpresaRepository")
 * @ExclusionPolicy("all")
 */
class Empresa extends BaseClass
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     * @Expose()
     * @ORM\Column(name="slogan", type="string", length=255, nullable=true)
     */
    private $slogan;

    /**
     * @var string
     * @Expose()
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * @var string
     * @Expose()
     * @ORM\Column(name="color", type="string", length=255, nullable=true)
     */
    private $color;

    /**
     * @var bool
     *
     * @ORM\Column(name="controla_stock", type="boolean", nullable=true)
     */
    private $controlaStock;

    /**
     *
     * @var String $telefonoPrincipal
     *
     * @ORM\Column(type="string" , length=50, nullable=true)
     */
    private $telefonoPrincipal;

    /**
     *
     * @var String $telefonoSecundario
     *
     * @ORM\Column(type="string" , length=50, nullable=true)
     */
    private $telefonoSecundario;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="sitio_web", type="string", length=255, nullable=true)
     */
    private $sitioWeb;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook", type="string", length=255, nullable=true)
     */
    private $facebook;

    /**
     * @var string
     *
     * @ORM\Column(name="instagram", type="string", length=255, nullable=true)
     */
    private $instagram;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter", type="string", length=255, nullable=true)
     */
    private $twitter;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Direccion", cascade={"persist"})
     * @ORM\JoinColumn(name="direccion_id", referencedColumnName="id")
     */
    private $direccion;

    /**
     * @var
     * @Exclude()
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PersonaEmpresa", mappedBy="empresa", cascade={"persist"})
     */
    private $personas;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="image_empresa", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Contacto")
     * @ORM\JoinColumn(name="contacto_id", referencedColumnName="id")
     */
    private $contacto;

    public function __toString()
    {
        return $this->getNombre();
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return persona
     */
    public function setImageFile(File $file = null)
    {
        $this->imageFile = $file;

        if ($file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->fechaActualizacion = new \DateTime('now');
        }

        return $this;
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
     * @return Empresa
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
     * Set slogan
     *
     * @param string $slogan
     * @return Empresa
     */
    public function setSlogan($slogan)
    {
        $this->slogan = $slogan;

        return $this;
    }

    /**
     * Get slogan
     *
     * @return string 
     */
    public function getSlogan()
    {
        return $this->slogan;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Empresa
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
     * Set color
     *
     * @param string $color
     * @return Empresa
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string 
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set controlaStock
     *
     * @param boolean $controlaStock
     * @return Empresa
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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return Empresa
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
     * @return Empresa
     */
    public function setFechaActualizacion($fechaActualizacion)
    {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    /**
     * Set direccion
     *
     * @param \AppBundle\Entity\Direccion $direccion
     * @return Empresa
     */
    public function setDireccion(\AppBundle\Entity\Direccion $direccion = null)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return \AppBundle\Entity\Direccion 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set contacto
     *
     * @param \AppBundle\Entity\Contacto $contacto
     * @return Empresa
     */
    public function setContacto(\AppBundle\Entity\Contacto $contacto = null)
    {
        $this->contacto = $contacto;

        return $this;
    }

    /**
     * Get contacto
     *
     * @return \AppBundle\Entity\Contacto 
     */
    public function getContacto()
    {
        return $this->contacto;
    }

    /**
     * Set creadoPor
     *
     * @param \UsuariosBundle\Entity\Usuario $creadoPor
     * @return Empresa
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
     * @return Empresa
     */
    public function setActualizadoPor(\UsuariosBundle\Entity\Usuario $actualizadoPor = null)
    {
        $this->actualizadoPor = $actualizadoPor;

        return $this;
    }

    /**
     * Set telefonoPrincipal
     *
     * @param string $telefonoPrincipal
     *
     * @return Empresa
     */
    public function setTelefonoPrincipal($telefonoPrincipal)
    {
        $this->telefonoPrincipal = $telefonoPrincipal;

        return $this;
    }

    /**
     * Get telefonoPrincipal
     *
     * @return string
     */
    public function getTelefonoPrincipal()
    {
        return $this->telefonoPrincipal;
    }

    /**
     * Set telefonoSecundario
     *
     * @param string $telefonoSecundario
     *
     * @return Empresa
     */
    public function setTelefonoSecundario($telefonoSecundario)
    {
        $this->telefonoSecundario = $telefonoSecundario;

        return $this;
    }

    /**
     * Get telefonoSecundario
     *
     * @return string
     */
    public function getTelefonoSecundario()
    {
        return $this->telefonoSecundario;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return Empresa
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set sitioWeb
     *
     * @param string $sitioWeb
     *
     * @return Empresa
     */
    public function setSitioWeb($sitioWeb)
    {
        $this->sitioWeb = $sitioWeb;

        return $this;
    }

    /**
     * Get sitioWeb
     *
     * @return string
     */
    public function getSitioWeb()
    {
        return $this->sitioWeb;
    }

    /**
     * Set facebook
     *
     * @param string $facebook
     *
     * @return Empresa
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Get facebook
     *
     * @return string
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Set instagram
     *
     * @param string $instagram
     *
     * @return Empresa
     */
    public function setInstagram($instagram)
    {
        $this->instagram = $instagram;

        return $this;
    }

    /**
     * Get instagram
     *
     * @return string
     */
    public function getInstagram()
    {
        return $this->instagram;
    }

    /**
     * Set twitter
     *
     * @param string $twitter
     *
     * @return Empresa
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;

        return $this;
    }

    /**
     * Get twitter
     *
     * @return string
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Empresa
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->personas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add persona
     *
     * @param \AppBundle\Entity\PersonaEmpresa $persona
     *
     * @return Empresa
     */
    public function addPersona(\AppBundle\Entity\PersonaEmpresa $persona)
    {
        $this->personas[] = $persona;

        return $this;
    }

    /**
     * Remove persona
     *
     * @param \AppBundle\Entity\PersonaEmpresa $persona
     */
    public function removePersona(\AppBundle\Entity\PersonaEmpresa $persona)
    {
        $this->personas->removeElement($persona);
    }

    /**
     * Get personas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPersonas()
    {
        return $this->personas;
    }
}

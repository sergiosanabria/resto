<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Base\BaseClass;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity
 * @Vich\Uploadable
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"cliente" = "Cliente", "persona" = "Persona", "empleado" = "Empleado"})
 * @ORM\Table(name="personas")
 *
 */
class Persona extends BaseClass
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
     *
     * @var String $nombre
     *
     * @ORM\Column(type="string" , length=150)
     */
    private $nombre;

    /**
     *
     * @var String $apellido
     *
     * @ORM\Column(type="string" , length=255, nullable=true)
     */
    private $apellido;

    /**
     *
     * @var String $fechaNacimiento
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaNacimiento;

    /**
     *
     * @var String $sexo
     *
     * @ORM\Column(type="string" , length=50, nullable=true)
     */
    private $sexo;

    /**
     *
     * @var String $perfil
     *
     * @ORM\Column(type="text" , nullable=true)
     */
    private $perfil;

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
     *
     * @var String $background
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $background;

    /**
     * @ORM\ManyToOne(targetEntity="UsuariosBundle\Entity\Usuario" ,  inversedBy="persona" )
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    private $usuario;

    /**
     * @var
     * @Exclude()
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Direccion", mappedBy="persona", cascade={"persist"})
     */
    private $direcciones;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="image_persona", fileNameProperty="image")
     * @var File
     */
    private $imageFile;


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
     * @SerializedName("nombreCompleto")
     * @VirtualProperty
     */
    public function getNombreCompleto()
    {
        return $this->getNombre() . ' ' . $this->getApellido();

    }

    /**
     * @SerializedName("direccion_principal")
     * @VirtualProperty
     */
    public function getDireccionPrincipal()
    {

        $direccion = $this->getFirstDireccion();
        if ($direccion && $direccion->getVisible()) {
            return array(
                'direccion' => $direccion->__toString(),
                'lat' => $direccion->getLatitud(),
                'long' => $direccion->getLongitud(),
                'id' => $direccion->getId(),

            );
        }

        return false;

    }

    /**
     * @SerializedName("direcciones_json_all")
     * @VirtualProperty
     */
    public function getAllDirecciones()
    {
        $array = [];

        if (count($this->direcciones)) {
            foreach ($this->direcciones as $direccion) {
                if ($direccion->getVisible()){
                    $array [] = array(
                        'direccion' => $direccion->__toString(),
                        'lat' => $direccion->getLatitud(),
                        'long' => $direccion->getLongitud(),
                        'id' => $direccion->getId(),
                        'activo' => $direccion->getActivo()

                    );
                }
            }
        }

        return $array;

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
     * Set nombreCompleto
     *
     * @param string $nombre
     *
     * @return Persona
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombreCompleto
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set apellido
     *
     * @param string $apellido
     *
     * @return Persona
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string
     */
    public function getApellido()
    {
        return $this->apellido;
    }


    /**
     * Set sexo
     *
     * @param string $sexo
     *
     * @return Persona
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get sexo
     *
     * @return string
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Set telefonoPrincipal
     *
     * @param string $telefonoPrincipal
     *
     * @return Persona
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
     * @return Persona
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
     * Set usuario
     *
     * @param \UsuariosBundle\Entity\Usuario $usuario
     *
     * @return Persona
     */
    public function setUsuario(\UsuariosBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \UsuariosBundle\Entity\Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Constructor
     */
    public function __construct()
    {

    }

    public function __toString()
    {
        return $this->getNombreCompleto();
    }


    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return Persona
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
     * @return Persona
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
     * @return Persona
     */
    public function setCreadoPor(\UsuariosBundle\Entity\Usuario $creadoPor = null)
    {
        $this->creadoPor = $creadoPor;

        return $this;
    }

    /**
     * Set actualizadoPor
     *
     * @param \UsuariosBundle\Entity\Usuarios $actualizadoPor
     *
     * @return Persona
     */
    public function setActualizadoPor(\UsuariosBundle\Entity\Usuario $actualizadoPor = null)
    {
        $this->actualizadoPor = $actualizadoPor;

        return $this;
    }

    /**
     * Set fechaNacimiento
     *
     * @param \DateTime $fechaNacimiento
     *
     * @return Persona
     */
    public function setFechaNacimiento(\DateTime $fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return \DateTime
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }


    /**
     * Set perfil
     *
     * @param string $perfil
     *
     * @return Persona
     */
    public function setPerfil($perfil)
    {
        $this->perfil = $perfil;

        return $this;
    }

    /**
     * Get perfil
     *
     * @return string
     */
    public function getPerfil()
    {
        return $this->perfil;
    }

    /**
     * Set background
     *
     * @param string $background
     *
     * @return Persona
     */
    public function setBackground($background)
    {
        $this->background = $background;

        return $this;
    }

    /**
     * Get background
     *
     * @return string
     */
    public function getBackground()
    {
        return $this->background;
    }


    /**
     * Set contacto
     *
     * @param \AppBundle\Entity\Contacto $contacto
     *
     * @return Persona
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
     * Set mail
     *
     * @param string $mail
     *
     * @return Persona
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
     * Add direccione
     *
     * @param \AppBundle\Entity\Direccion $direccione
     *
     * @return Persona
     */
    public function addDireccione(\AppBundle\Entity\Direccion $direccione)
    {
        $this->direcciones[] = $direccione;

        $direccione->setActivo(true);

        $direccione->setPersona($this);

        return $this;
    }

    /**
     * Remove direccione
     *
     * @param \AppBundle\Entity\Direccion $direccione
     */
    public function removeDireccione(\AppBundle\Entity\Direccion &$direccione)
    {
        $direccione->setActivo(false);

        $this->direcciones->removeElement($direccione);
    }

    /**
     * Get direcciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDirecciones()
    {
        $arrayColl = new ArrayCollection();

        if ($this->direcciones && count($this->direcciones)) {
            foreach ($this->direcciones as $direccione) {
                if ($direccione->getActivo()) {
                    $arrayColl->add($direccione);
                }
            }
        }


        return $arrayColl;
    }

    public function getFirstDireccion()
    {
        return $this->getDirecciones()->first();
    }


    /**
     * Set image
     *
     * @param string $image
     *
     * @return Persona
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
}

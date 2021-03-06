<?php

namespace UsuariosBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="UsuarioRepository")
 * @ORM\Table(name="fos_user")
 * @UniqueEntity("username", message="El usuario ya está siendo utilizado")
 * @UniqueEntity("email", message="El mail ya está siendo utilizado")
 */
class Usuario extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Persona", mappedBy="usuario", cascade={"persist"})
     */
    private $persona;

    /**
     * @var boolean
     *
     * @ORM\Column(name="root", type="boolean")
     */
    private $root = false;


    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\UsuarioRol", mappedBy="usuario", cascade={"persist"})
     */
    private $rolesUsuario;

    /**
     * @var datetime $creado
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="creado", type="datetime")
     */
    private $creado;

    /**
     * @var datetime $actualizado
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="actualizado",type="datetime")
     */
    private $actualizado;

    /**
     * @var integer $creadoPor
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="UsuariosBundle\Entity\Usuario")
     * @ORM\JoinColumn(name="creado_por", referencedColumnName="id", nullable=true)
     */
    private $creadoPor;

    /**
     * @var integer $actualizadoPor
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="UsuariosBundle\Entity\Usuario")
     * @ORM\JoinColumn(name="actualizado_por", referencedColumnName="id", nullable=true)
     */
    private $actualizadoPor;

    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->roles = array('ROLE_USER');
        $this->empresa = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getPersonaActiva()
    {
        if (count($this->persona)) {
            return $this->getPersona()->first();
        }
    }


    /**
     * Set creado
     *
     * @param \DateTime $creado
     *
     * @return Usuario
     */
    public function setCreado($creado)
    {
        $this->creado = $creado;

        return $this;
    }

    /**
     * Get creado
     *
     * @return \DateTime
     */
    public function getCreado()
    {
        return $this->creado;
    }

    /**
     * Set actualizado
     *
     * @param \DateTime $actualizado
     *
     * @return Usuario
     */
    public function setActualizado($actualizado)
    {
        $this->actualizado = $actualizado;

        return $this;
    }

    /**
     * Get actualizado
     *
     * @return \DateTime
     */
    public function getActualizado()
    {
        return $this->actualizado;
    }

    /**
     * Set creadoPor
     *
     * @param \UsuariosBundle\Entity\Usuario $creadoPor
     *
     * @return Usuario
     */
    public function setCreadoPor(\UsuariosBundle\Entity\Usuario $creadoPor = null)
    {
        $this->creadoPor = $creadoPor;

        return $this;
    }

    /**
     * Get creadoPor
     *
     * @return \UsuariosBundle\Entity\Usuario
     */
    public function getCreadoPor()
    {
        return $this->creadoPor;
    }

    /**
     * Set actualizadoPor
     *
     * @param \UsuariosBundle\Entity\Usuario $actualizadoPor
     *
     * @return Usuario
     */
    public function setActualizadoPor(\UsuariosBundle\Entity\Usuario $actualizadoPor = null)
    {
        $this->actualizadoPor = $actualizadoPor;

        return $this;
    }

    /**
     * Get actualizadoPor
     *
     * @return \UsuariosBundle\Entity\Usuario
     */
    public function getActualizadoPor()
    {
        return $this->actualizadoPor;
    }

    /**
     * Add persona
     *
     * @param \AppBundle\Entity\Persona $persona
     *
     * @return Usuario
     */
    public function addPersona(\AppBundle\Entity\Persona &$persona)
    {
        $persona->setUsuario($this);

        $this->persona[] = $persona;

        return $this;
    }

    /**
     * Remove persona
     *
     * @param \AppBundle\Entity\Persona $persona
     */
    public function removePersona(\AppBundle\Entity\Persona $persona)
    {
        $this->persona->removeElement($persona);
    }

    /**
     * Get persona
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPersona()
    {
        return $this->persona;
    }


    /**
     * Add rolesUsuario
     *
     * @param \AppBundle\Entity\UsuarioRol $rolesUsuario
     *
     * @return Usuario
     */
    public function addRolesUsuario(\AppBundle\Entity\UsuarioRol &$rolesUsuario)
    {
        $rolesUsuario->setUsuario($this);

        $rolesUsuario->setActivo(true);

        $this->rolesUsuario[] = $rolesUsuario;

        return $this;
    }

    /**
     * Remove rolesUsuario
     *
     * @param \AppBundle\Entity\UsuarioRol $rolesUsuario
     */
    public function removeRolesUsuario(\AppBundle\Entity\UsuarioRol &$rolesUsuario)
    {
        $rolesUsuario->setActivo(false);

        $this->rolesUsuario->removeElement($rolesUsuario);
    }

    /**
     * Get rolesUsuario
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRolesUsuario()
    {
        $array = new ArrayCollection();
        if ($this->rolesUsuario && count($this->rolesUsuario)) {
            foreach ($this->rolesUsuario as $item) {
                if ($item->getActivo()) {
                    $array->add($item);
                }
            }
        }

        return $array;
    }


    /**
     * Set root
     *
     * @param boolean $root
     *
     * @return Usuario
     */
    public function setRoot($root)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Get root
     *
     * @return boolean
     */
    public function getRoot()
    {
        return $this->root;
    }
}

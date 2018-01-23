<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Rol
 *
 * @ORM\Table(name="rol")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RolRepository")
 */
class Rol
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
     * @var
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\RolRoute", mappedBy="rol", cascade={"persist"})
     */
    private $routes;

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\UsuarioRol", mappedBy="rol", cascade={"persist"})
     */
    private $rolesUsuario;

    /**
     * Devuelve las rutas solamente, sin la asociaion
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRoutesOnly()
    {
        $result = new ArrayCollection();

        if (count($this->routes) > 0) {

            foreach ($this->routes as $route) {
                $result->add($route->getRoute());
            }
        }

        return $result;
    }

    public function tieneRoute($route)
    {
        foreach ($this->getRoutesOnly() as $route) {

            if ($route == $route->getRoute()){
                return $route;
            }
        }

        return false;
    }

    public function __toString()
    {
        return $this->getNombre();
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
     * @return Rol
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
     * @return Rol
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
     * Constructor
     */
    public function __construct()
    {
        $this->routes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add route
     *
     * @param \AppBundle\Entity\RolRoute $route
     *
     * @return Rol
     */
    public function addRoute(\AppBundle\Entity\RolRoute $route)
    {
        $this->routes[] = $route;

        return $this;
    }

    /**
     * Remove route
     *
     * @param \AppBundle\Entity\RolRoute $route
     */
    public function removeRoute(\AppBundle\Entity\RolRoute $route)
    {
        $this->routes->removeElement($route);
    }

    /**
     * Get routes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Add rolesUsuario
     *
     * @param \AppBundle\Entity\UsuarioRol $rolesUsuario
     *
     * @return Rol
     */
    public function addRolesUsuario(\AppBundle\Entity\UsuarioRol $rolesUsuario)
    {
        $this->rolesUsuario[] = $rolesUsuario;

        return $this;
    }

    /**
     * Remove rolesUsuario
     *
     * @param \AppBundle\Entity\UsuarioRol $rolesUsuario
     */
    public function removeRolesUsuario(\AppBundle\Entity\UsuarioRol $rolesUsuario)
    {
        $this->rolesUsuario->removeElement($rolesUsuario);
    }

    /**
     * Get rolesUsuario
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRolesUsuario()
    {
        return $this->rolesUsuario;
    }
}

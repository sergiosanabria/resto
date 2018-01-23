<?php

namespace UsuariosBundle\Services;


use AppBundle\Entity\Route;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use UsuariosBundle\Entity\Usuario;

class UsuarioManager
{

    private $em;
    private $user;
    private $session;

    public function __construct(EntityManager $em, Session $session, TokenStorage $tokenStorage)
    {
        $this->em = $em;
        $this->session = $session;

        if (!is_null($tokenStorage->getToken())){
            $this->user = $tokenStorage->getToken()->getUser();
        }

    }


    public function getRoles()
    {

        $roles = $this->em->getRepository("AppBundle:Rol")->getByUsuario($this->user);

        return $roles;

    }

    public function getRolLogueado()
    {

        $rol = $this->em->getRepository("AppBundle:Rol")->find($this->session->get("rolId"));

        return $rol;

    }


    public function tienePermiso($route, $startWith = false)
    {
        if ($this->getUser()->getRoot()) {
            return true;
        }

        $routeDesactivado = $this->em->getRepository(Route::class)->getRouteDesactivado($route, $startWith);

        if ($routeDesactivado) {
            return true;
        }

        $rolLogueado = $this->getRolLogueado();

        return $rolLogueado->tieneRoute($route);
    }


    /**
     * @return Usuario
     */
    public function getUser()
    {
        return $this->user;
    }


}
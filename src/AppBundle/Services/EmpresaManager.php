<?php

namespace AppBundle\Services;


use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class EmpresaManager
{

    private $em;
    private $session;

    public function __construct(EntityManager $em, Session $session)
    {
        $this->em = $em;
        $this->session = $session;
    }


    public function getEmpresaLogueada()
    {

        $empresaId = $this->session->get("empresaId");

        if ($empresaId) {
            return $this->em->getRepository("AppBundle:Empresa")->find($empresaId);
        } else {
            return null;
        }


    }


}
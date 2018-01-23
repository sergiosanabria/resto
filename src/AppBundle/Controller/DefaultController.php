<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Direccion;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AppBundle:Default:index.html.twig');
    }

    public function setDireccionActivoAction(Request $request, Direccion $direccion)
    {

        $em = $this->getDoctrine()->getManager();

        $direccion->setVisible(!$direccion->getVisible());

        $em->flush();

        return new JsonResponse('ok');
    }
}

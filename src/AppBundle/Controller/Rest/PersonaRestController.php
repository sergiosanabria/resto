<?php

namespace AppBundle\Controller\Rest;

use AppBundle\Entity\Image;
use AppBundle\Entity\Persona;
use AppBundle\Form\PersonaType;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use UsuarioBundle\Entity\Usuario;

class PersonaRestController extends FOSRestController
{
    public function putPersonaAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $persona = $em->getRepository("AppBundle:Persona")->findOneBy(array('usuario' => $this->getUser()));

        $fecha = new \DateTime($request->get('fechaNacimiento'));

        $persona->setFechaNacimiento($fecha);

        $request->request->remove('fechaNacimiento');

        $form = $this->createForm(PersonaType::class, $persona, ['method' => 'put']);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();
            $vista = $this->view($this->getUser(),
                200);
        } else {
            $vista = $this->view(false,
                555);
        }


        return $this->handleView($vista);
    }

    public function putPersonaBackgroundAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        /**
         * @var $persona Persona
         */
        $persona = $em->getRepository("AppBundle:Persona")->findOneBy(array('usuario' => $this->getUser()));

        $persona->setBackground($request->get('bg'));

        $em->flush();
        $vista = $this->view($this->getUser(),
            200);

        return $this->handleView($vista);
    }

    public function postPersonaImageAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $persona = $em->getRepository("AppBundle:Persona")->findOneBy(array('usuario' => $this->getUser()));

        if ($persona) {
            $imagenFile = $request->files->get('file');

            if ($persona->getImage()) {
                $image = $persona->getImage();
            } else {
                $image = new Image();
            }


            $image->setImageFile($imagenFile);


            $persona->setImage($image);


            $em->flush();
        }


        $vista = $this->view('ok',
            200);

        return $this->handleView($vista);
    }

    public function getCheckAction()
    {
        $vista = $this->view($this->getUser(),
            200);

        return $this->handleView($vista);
    }
}

<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Direccion;
use AppBundle\Entity\PersonaEmpresa;
use AppBundle\Services\DireccionManager;
use Geocoder\Query\GeocodeQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Cliente;
use AppBundle\Form\ClienteType;
use UsuariosBundle\Entity\Usuario;

/**
 * Cliente controller.
 *
 */
class ClienteController extends Controller
{
    /**
     * Lists all Cliente entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $clientes = $em->getRepository('AppBundle:Cliente')->findAll();

        return $this->render('cliente/index.html.twig', array(
            'clientes' => $clientes,
        ));
    }

    /**
     * Creates a new Cliente entity.
     *
     */
    public function newAction(Request $request)
    {
        $cliente = new Cliente();
        $form = $this->createForm('AppBundle\Form\ClienteType', $cliente);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $this->container
                ->get(DireccionManager::class)
                ->setLocationCollDireccion($cliente->getDirecciones());

            $personaEmpresa = new PersonaEmpresa();

            $personaEmpresa->setActivo(true);
            $personaEmpresa->setPersona($cliente);
            $personaEmpresa->setEmpresa($this->get("AppBundle\Services\EmpresaManager")->getEmpresaLogueada());

//            $user->setUsername()
//            $user->setPlainPassword(md5(random_bytes(6)));
//            $user->setEmail($cliente->getMail());

            $em->persist($cliente);
            $em->persist($personaEmpresa);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('%entity% Creado Correctamente',
                    array('%entity%' => 'Cliente'),
                    'flashes')
            );

            return $this->redirectToRoute('cliente_show', array('id' => $cliente->getId()));
        }

        return $this->render('cliente/new.html.twig', array(
            'cliente' => $cliente,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Cliente entity.
     *
     */
    public function showAction(Cliente $cliente)
    {
        $deleteForm = $this->createDeleteForm($cliente);

        return $this->render('cliente/show.html.twig', array(
            'cliente' => $cliente,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Cliente entity.
     *
     */
    public function editAction(Request $request, Cliente $cliente)
    {
        $deleteForm = $this->createDeleteForm($cliente);
        $editForm = $this->createForm('AppBundle\Form\ClienteType', $cliente);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->container
                ->get(DireccionManager::class)
                ->setLocationCollDireccion($cliente->getDirecciones());


            $em = $this->getDoctrine()->getManager();
            $em->persist($cliente);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('%entity% Actualizado Correctamente',
                    array('%entity%' => 'Cliente'),
                    'flashes')
            );

            return $this->redirectToRoute('cliente_edit', array('id' => $cliente->getId()));
        }

        return $this->render('cliente/edit.html.twig', array(
            'cliente' => $cliente,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Cliente entity.
     *
     */
    public function deleteAction(Request $request, Cliente $cliente)
    {
        $form = $this->createDeleteForm($cliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($cliente);
            $em->flush();
        }

        return $this->redirectToRoute('cliente_index');
    }

    /**
     * Creates a form to delete a Cliente entity.
     *
     * @param Cliente $cliente The Cliente entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Cliente $cliente)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cliente_delete', array('id' => $cliente->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     *
     * Ajax que devuelve los ingredienetes de una empresa
     */

    public function getPorEmpresaAction(Request $request)
    {

        $em = $this->getDoctrine();

        $value = $request->get('q');
        $limit = $request->get('page_limit');
//        $entities = $em->getRepository('AppBundle:Ingrediente')->findAll($value, $limit, true);
        $entities = $em->getRepository('AppBundle:Cliente')->getPorEmpresa($value, $limit);

        $json = array();

        if (!count($entities)) {
            $json[] = array(
                'text' => 'No se encontraron coincidencias',
                'id' => ''
            );
        } else {

            foreach ($entities as $entity) {
                $json[] = array(
                    'id' => $entity->getId(),
                    //'label' => $entity[$property],
                    'text' => $entity->getNombre()
                );
            }
        }

        return new JsonResponse($json);
    }



}

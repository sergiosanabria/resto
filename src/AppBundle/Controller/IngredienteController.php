<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Ingrediente;
use AppBundle\Form\IngredienteType;

/**
 * Ingrediente controller.
 *
 */
class IngredienteController extends Controller
{
    /**
     * Lists all Ingrediente entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $ingredientes = $em->getRepository('AppBundle:Ingrediente')->findAll();

        return $this->render('ingrediente/index.html.twig', array(
            'ingredientes' => $ingredientes,
        ));
    }

    /**
     * Creates a new Ingrediente entity.
     *
     */
    public function newAction(Request $request)
    {
        $ingrediente = new Ingrediente();
        $form = $this->createForm('AppBundle\Form\IngredienteType', $ingrediente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ingrediente);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('%entity% Creado Correctamente',
                    array('%entity%' => 'Ingrediente'),
                    'flashes')
            );

            return $this->redirectToRoute('ingrediente_show', array('id' => $ingrediente->getId()));
        }

        return $this->render('ingrediente/new.html.twig', array(
            'ingrediente' => $ingrediente,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Ingrediente entity.
     *
     */
    public function showAction(Ingrediente $ingrediente)
    {
        $deleteForm = $this->createDeleteForm($ingrediente);

        return $this->render('ingrediente/show.html.twig', array(
            'ingrediente' => $ingrediente,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Ingrediente entity.
     *
     */
    public function editAction(Request $request, Ingrediente $ingrediente)
    {
        $deleteForm = $this->createDeleteForm($ingrediente);
        $editForm = $this->createForm('AppBundle\Form\IngredienteType', $ingrediente);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ingrediente);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('%entity% Actualizado Correctamente',
                    array('%entity%' => 'Ingrediente'),
                    'flashes')
            );

            return $this->redirectToRoute('ingrediente_edit', array('id' => $ingrediente->getId()));
        }

        return $this->render('ingrediente/edit.html.twig', array(
            'ingrediente' => $ingrediente,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Ingrediente entity.
     *
     */
    public function deleteAction(Request $request, Ingrediente $ingrediente)
    {
        $form = $this->createDeleteForm($ingrediente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ingrediente);
            $em->flush();
        }

        return $this->redirectToRoute('ingrediente_index');
    }

    /**
     * Creates a form to delete a Ingrediente entity.
     *
     * @param Ingrediente $ingrediente The Ingrediente entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Ingrediente $ingrediente)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ingrediente_delete', array('id' => $ingrediente->getId())))
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
        $entities = $em->getRepository('AppBundle:Ingrediente')->getPorEmpresa($value, $limit);

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

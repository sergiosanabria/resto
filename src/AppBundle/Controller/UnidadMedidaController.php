<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\UnidadMedida;
use AppBundle\Form\UnidadMedidaType;

/**
 * UnidadMedida controller.
 *
 */
class UnidadMedidaController extends Controller
{
    /**
     * Lists all UnidadMedida entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $unidadMedidas = $em->getRepository('AppBundle:UnidadMedida')->findAll();

        return $this->render('unidadmedida/index.html.twig', array(
            'unidadMedidas' => $unidadMedidas,
        ));
    }

    /**
     * Creates a new UnidadMedida entity.
     *
     */
    public function newAction(Request $request)
    {
        $unidadMedida = new UnidadMedida();
        $form = $this->createForm('AppBundle\Form\UnidadMedidaType', $unidadMedida);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($unidadMedida);
            $em->flush();

            $this->get( 'session' )->getFlashBag()->add(
            'success',
            $this->get( 'translator' )->trans( '%entity% Creado Correctamente',
            array( '%entity%' => 'UnidadMedida' ),
            'flashes' )
            );

            return $this->redirectToRoute('unidadmedida_show', array('id' => $unidadMedida->getId()));
        }

        return $this->render('unidadmedida/new.html.twig', array(
            'unidadMedida' => $unidadMedida,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a UnidadMedida entity.
     *
     */
    public function showAction(UnidadMedida $unidadMedida)
    {
        $deleteForm = $this->createDeleteForm($unidadMedida);

        return $this->render('unidadmedida/show.html.twig', array(
            'unidadMedida' => $unidadMedida,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing UnidadMedida entity.
     *
     */
    public function editAction(Request $request, UnidadMedida $unidadMedida)
    {
        $deleteForm = $this->createDeleteForm($unidadMedida);
        $editForm = $this->createForm('AppBundle\Form\UnidadMedidaType', $unidadMedida);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($unidadMedida);
            $em->flush();

            $this->get( 'session' )->getFlashBag()->add(
            'success',
            $this->get( 'translator' )->trans( '%entity% Actualizado Correctamente',
            array( '%entity%' => 'UnidadMedida' ),
            'flashes' )
            );

            return $this->redirectToRoute('unidadmedida_edit', array('id' => $unidadMedida->getId()));
        }

        return $this->render('unidadmedida/edit.html.twig', array(
            'unidadMedida' => $unidadMedida,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a UnidadMedida entity.
     *
     */
    public function deleteAction(Request $request, UnidadMedida $unidadMedida)
    {
        $form = $this->createDeleteForm($unidadMedida);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($unidadMedida);
            $em->flush();
        }

        return $this->redirectToRoute('unidadmedida_index');
    }

    /**
     * Creates a form to delete a UnidadMedida entity.
     *
     * @param UnidadMedida $unidadMedida The UnidadMedida entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(UnidadMedida $unidadMedida)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('unidadmedida_delete', array('id' => $unidadMedida->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

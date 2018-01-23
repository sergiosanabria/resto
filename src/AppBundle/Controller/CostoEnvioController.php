<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\CostoEnvio;
use AppBundle\Form\CostoEnvioType;

/**
 * CostoEnvio controller.
 *
 */
class CostoEnvioController extends Controller
{
    /**
     * Lists all CostoEnvio entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $costoEnvios = $em->getRepository('AppBundle:CostoEnvio')->findAll();

        return $this->render('costoenvio/index.html.twig', array(
            'costoEnvios' => $costoEnvios,
        ));
    }

    /**
     * Creates a new CostoEnvio entity.
     *
     */
    public function newAction(Request $request)
    {
        $costoEnvio = new CostoEnvio();
        $form = $this->createForm('AppBundle\Form\CostoEnvioType', $costoEnvio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($costoEnvio);
            $em->flush();

            $this->get( 'session' )->getFlashBag()->add(
            'success',
            $this->get( 'translator' )->trans( '%entity% Creado Correctamente',
            array( '%entity%' => 'CostoEnvio' ),
            'flashes' )
            );

            return $this->redirectToRoute('costoenvio_show', array('id' => $costoEnvio->getId()));
        }

        return $this->render('costoenvio/new.html.twig', array(
            'costoEnvio' => $costoEnvio,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CostoEnvio entity.
     *
     */
    public function showAction(CostoEnvio $costoEnvio)
    {
        $deleteForm = $this->createDeleteForm($costoEnvio);

        return $this->render('costoenvio/show.html.twig', array(
            'costoEnvio' => $costoEnvio,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing CostoEnvio entity.
     *
     */
    public function editAction(Request $request, CostoEnvio $costoEnvio)
    {
        $deleteForm = $this->createDeleteForm($costoEnvio);
        $editForm = $this->createForm('AppBundle\Form\CostoEnvioType', $costoEnvio);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($costoEnvio);
            $em->flush();

            $this->get( 'session' )->getFlashBag()->add(
            'success',
            $this->get( 'translator' )->trans( '%entity% Actualizado Correctamente',
            array( '%entity%' => 'CostoEnvio' ),
            'flashes' )
            );

            return $this->redirectToRoute('costoenvio_edit', array('id' => $costoEnvio->getId()));
        }

        return $this->render('costoenvio/edit.html.twig', array(
            'costoEnvio' => $costoEnvio,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CostoEnvio entity.
     *
     */
    public function deleteAction(Request $request, CostoEnvio $costoEnvio)
    {
        $form = $this->createDeleteForm($costoEnvio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($costoEnvio);
            $em->flush();
        }

        return $this->redirectToRoute('costoenvio_index');
    }

    /**
     * Creates a form to delete a CostoEnvio entity.
     *
     * @param CostoEnvio $costoEnvio The CostoEnvio entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CostoEnvio $costoEnvio)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('costoenvio_delete', array('id' => $costoEnvio->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

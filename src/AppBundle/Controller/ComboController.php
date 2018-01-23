<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Combo;
use AppBundle\Form\ComboType;

/**
 * Combo controller.
 *
 */
class ComboController extends Controller
{
    /**
     * Lists all Combo entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $combos = $em->getRepository('AppBundle:Combo')->findAll();

        return $this->render('combo/index.html.twig', array(
            'combos' => $combos,
        ));
    }

    /**
     * Creates a new Combo entity.
     *
     */
    public function newAction(Request $request)
    {
        $combo = new Combo();
        $form = $this->createForm('AppBundle\Form\ComboType', $combo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($combo);
            $em->flush();

            $this->get( 'session' )->getFlashBag()->add(
            'success',
            $this->get( 'translator' )->trans( '%entity% Creado Correctamente',
            array( '%entity%' => 'Combo' ),
            'flashes' )
            );

            return $this->redirectToRoute('combo_show', array('id' => $combo->getId()));
        }

        return $this->render('combo/new.html.twig', array(
            'combo' => $combo,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Combo entity.
     *
     */
    public function showAction(Combo $combo)
    {
        $deleteForm = $this->createDeleteForm($combo);

        return $this->render('combo/show.html.twig', array(
            'combo' => $combo,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Combo entity.
     *
     */
    public function editAction(Request $request, Combo $combo)
    {
        $deleteForm = $this->createDeleteForm($combo);
        $editForm = $this->createForm('AppBundle\Form\ComboType', $combo);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($combo);
            $em->flush();

            $this->get( 'session' )->getFlashBag()->add(
            'success',
            $this->get( 'translator' )->trans( '%entity% Actualizado Correctamente',
            array( '%entity%' => 'Combo' ),
            'flashes' )
            );

            return $this->redirectToRoute('combo_edit', array('id' => $combo->getId()));
        }

        return $this->render('combo/edit.html.twig', array(
            'combo' => $combo,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Combo entity.
     *
     */
    public function deleteAction(Request $request, Combo $combo)
    {
        $form = $this->createDeleteForm($combo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($combo);
            $em->flush();
        }

        return $this->redirectToRoute('combo_index');
    }

    /**
     * Creates a form to delete a Combo entity.
     *
     * @param Combo $combo The Combo entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Combo $combo)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('combo_delete', array('id' => $combo->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

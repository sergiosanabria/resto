<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Producto;
use AppBundle\Form\ProductoType;

/**
 * Producto controller.
 *
 */
class ProductoController extends Controller
{
    /**
     * Lists all Producto entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $empresa = $this->get("AppBundle\Services\EmpresaManager")->getEmpresaLogueada();

        $productos = $em->getRepository('AppBundle:Producto')->getListado(array(), $empresa);

        return $this->render('producto/index.html.twig', array(
            'productos' => $productos,
        ));
    }

    /**
     * Creates a new Producto entity.
     *
     */
    public function newAction(Request $request)
    {
        $producto = new Producto();
        $form = $this->createForm('AppBundle\Form\ProductoType', $producto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($producto);
            $em->flush();

            $this->get( 'session' )->getFlashBag()->add(
            'success',
            $this->get( 'translator' )->trans( '%entity% Creado Correctamente',
            array( '%entity%' => 'Producto' ),
            'flashes' )
            );

            return $this->redirectToRoute('producto_show', array('id' => $producto->getId()));
        }

        return $this->render('producto/new.html.twig', array(
            'producto' => $producto,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Producto entity.
     *
     */
    public function showAction(Producto $producto)
    {
        $deleteForm = $this->createDeleteForm($producto);

        return $this->render('producto/show.html.twig', array(
            'producto' => $producto,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Producto entity.
     *
     */
    public function editAction(Request $request, Producto $producto)
    {
        $deleteForm = $this->createDeleteForm($producto);
        $editForm = $this->createForm('AppBundle\Form\ProductoType', $producto);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($producto);
            $em->flush();

            $this->get( 'session' )->getFlashBag()->add(
            'success',
            $this->get( 'translator' )->trans( '%entity% Actualizado Correctamente',
            array( '%entity%' => 'Producto' ),
            'flashes' )
            );

            return $this->redirectToRoute('producto_edit', array('id' => $producto->getId()));
        }

        return $this->render('producto/edit.html.twig', array(
            'producto' => $producto,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Producto entity.
     *
     */
    public function deleteAction(Request $request, Producto $producto)
    {
        $form = $this->createDeleteForm($producto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($producto);
            $em->flush();
        }

        return $this->redirectToRoute('producto_index');
    }

    /**
     * Creates a form to delete a Producto entity.
     *
     * @param Producto $producto The Producto entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Producto $producto)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('producto_delete', array('id' => $producto->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

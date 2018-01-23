<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Precio;
use AppBundle\Form\PrecioType;

/**
 * Precio controller.
 *
 */
class PrecioController extends Controller
{

    private $PRODUCTO = "producto";
    private $COMBO = "combo";

    /**
     * Lists all Precio entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $precios = $em->getRepository('AppBundle:Precio')->findAll();

        return $this->render('precio/index.html.twig', array(
            'precios' => $precios,
        ));
    }

    /**
     * Creates a new Precio entity.
     *
     */
    public function newAction(Request $request)
    {
        $precio = new Precio();
        $form = $this->createForm('AppBundle\Form\PrecioType', $precio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($precio);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('%entity% Creado Correctamente',
                    array('%entity%' => 'Precio'),
                    'flashes')
            );

            return $this->redirectToRoute('precio_show', array('id' => $precio->getId()));
        }

        return $this->render('precio/new.html.twig', array(
            'precio' => $precio,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new Precio entity.
     *
     */
    public function newHistoricoAction(Request $request, $tipo, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $precio = new Precio();

        if ($tipo == $this->COMBO) {

            $combo = $em->getRepository('AppBundle:Combo')->find($id);

            $precio->setCombo($combo);

        }


        if ($tipo == $this->PRODUCTO) {

            $combo = $em->getRepository('AppBundle:Producto')->find($id);

            $precio->setProducto($combo);

        }

        $form = $this->createForm('AppBundle\Form\PrecioType', $precio);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($tipo == $this->COMBO) {

                if ($precio->getTipoPrecio() == Precio::$tipoPrecioCosto) {
                    $precio->getCombo()->setPrecioDeCosto($precio->getPrecio());
                }


                if ($precio->getTipoPrecio() == Precio::$tipoPrecioVenta) {
                    $precio->getCombo()->setPrecioDeVenta($precio->getPrecio());
                }

            }


            if ($tipo == $this->PRODUCTO) {

                if ($precio->getTipoPrecio() == Precio::$tipoPrecioCosto) {
                    $precio->getProducto()->setPrecioDeCosto($precio->getPrecio());
                }


                if ($precio->getTipoPrecio() == Precio::$tipoPrecioVenta) {
                    $precio->getProducto()->setPrecioDeVenta($precio->getPrecio());
                }

            }

            $em->persist($precio);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('%entity% Creado Correctamente',
                    array('%entity%' => 'Precio'),
                    'flashes')
            );

            return $this->redirectToRoute('precio_show', array('id' => $precio->getId()));
        }

        return $this->render('precio/new.html.twig', array(
            'precio' => $precio,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Precio entity.
     *
     */
    public function showAction(Precio $precio)
    {
        $deleteForm = $this->createDeleteForm($precio);

        return $this->render('precio/show.html.twig', array(
            'precio' => $precio,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Precio entity.
     *
     */
    public function editAction(Request $request, Precio $precio)
    {
        $deleteForm = $this->createDeleteForm($precio);
        $editForm = $this->createForm('AppBundle\Form\PrecioType', $precio);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($precio);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('%entity% Actualizado Correctamente',
                    array('%entity%' => 'Precio'),
                    'flashes')
            );

            return $this->redirectToRoute('precio_edit', array('id' => $precio->getId()));
        }

        return $this->render('precio/edit.html.twig', array(
            'precio' => $precio,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Precio entity.
     *
     */
    public function deleteAction(Request $request, Precio $precio)
    {
        $form = $this->createDeleteForm($precio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($precio);
            $em->flush();
        }

        return $this->redirectToRoute('precio_index');
    }

    /**
     * Creates a form to delete a Precio entity.
     *
     * @param Precio $precio The Precio entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Precio $precio)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('precio_delete', array('id' => $precio->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}

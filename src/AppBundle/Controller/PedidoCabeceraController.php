<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CostoEnvio;
use AppBundle\Entity\SectorMesa;
use AppBundle\Services\EmpresaManager;
use AppBundle\Services\MapeoManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\PedidoCabecera;
use AppBundle\Form\PedidoCabeceraType;

/**
 * PedidoCabecera controller.
 *
 */
class PedidoCabeceraController extends Controller
{
    /**
     * Lists all PedidoCabecera entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $pedidoCabeceras = $em->getRepository('AppBundle:PedidoCabecera')->findAll();

        return $this->render('pedidocabecera/index.html.twig', array(
            'pedidoCabeceras' => $pedidoCabeceras,
        ));
    }

    /**
     * Creates a new PedidoCabecera entity.
     *
     */
    public function newAction(Request $request)
    {
        $pedidoCabecera = new PedidoCabecera();
        $form = $this->createForm('AppBundle\Form\PedidoCabeceraType', $pedidoCabecera);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($pedidoCabecera);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('%entity% Creado Correctamente',
                    array('%entity%' => 'PedidoCabecera'),
                    'flashes')
            );

            return $this->redirectToRoute('pedidocabecera_show', array('id' => $pedidoCabecera->getId()));
        }

        $empresa = $this->get(EmpresaManager::class)->getEmpresaLogueada();

        $costos = $this->get(MapeoManager::class)->getArrayData(['id', 'nombre', 'descripcion', 'precio'], $em->getRepository(CostoEnvio::class)->findBy(
            array(
                'activo' => true
            ),
            array(
                'precio' => 'ASC'
            )
        ));

        $sectores = $em->getRepository(SectorMesa::class)->findBy(
            array(
                'activo' => true,
                'empresa' => $empresa
            ),
            array(
                'nombre' => 'ASC'
            )
        );

        $sectoresArray = [];

        if ($sectores) {
            foreach ($sectores as $sector) {
                $sectoresArray [] = array(
                    'id'=> $sector->getId(),
                    'mesas'=> $sector->getMatrixMesa(),
                );
            }
        }


        return $this->render('pedidocabecera/new.html.twig', array(
            'pedidoCabecera' => $pedidoCabecera,
            'sectores' => json_encode($sectoresArray),
            'costosEnvio' => json_encode($costos),
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a PedidoCabecera entity.
     *
     */
    public function showAction(PedidoCabecera $pedidoCabecera)
    {
        $deleteForm = $this->createDeleteForm($pedidoCabecera);

        return $this->render('pedidocabecera/show.html.twig', array(
            'pedidoCabecera' => $pedidoCabecera,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing PedidoCabecera entity.
     *
     */
    public function editAction(Request $request, PedidoCabecera $pedidoCabecera)
    {
        $deleteForm = $this->createDeleteForm($pedidoCabecera);
        $editForm = $this->createForm('AppBundle\Form\PedidoCabeceraType', $pedidoCabecera);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pedidoCabecera);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('%entity% Actualizado Correctamente',
                    array('%entity%' => 'PedidoCabecera'),
                    'flashes')
            );

            return $this->redirectToRoute('pedidocabecera_edit', array('id' => $pedidoCabecera->getId()));
        }

        return $this->render('pedidocabecera/edit.html.twig', array(
            'pedidoCabecera' => $pedidoCabecera,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a PedidoCabecera entity.
     *
     */
    public function deleteAction(Request $request, PedidoCabecera $pedidoCabecera)
    {
        $form = $this->createDeleteForm($pedidoCabecera);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($pedidoCabecera);
            $em->flush();
        }

        return $this->redirectToRoute('pedidocabecera_index');
    }

    /**
     * Creates a form to delete a PedidoCabecera entity.
     *
     * @param PedidoCabecera $pedidoCabecera The PedidoCabecera entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PedidoCabecera $pedidoCabecera)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('pedidocabecera_delete', array('id' => $pedidoCabecera->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}

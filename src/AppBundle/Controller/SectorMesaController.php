<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Mesa;
use AppBundle\Services\EmpresaManager;
use AppBundle\Services\SectorManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\SectorMesa;
use AppBundle\Form\SectorMesaType;

/**
 * SectorMesa controller.
 *
 */
class SectorMesaController extends Controller
{
    /**
     * Lists all SectorMesa entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $sectorMesas = $em->getRepository('AppBundle:SectorMesa')->findAll();

        return $this->render('sectormesa/index.html.twig', array(
            'sectorMesas' => $sectorMesas,
        ));
    }

    /**
     * Creates a new SectorMesa entity.
     *
     */
    public function newAction(Request $request)
    {
        $sectorMesa = new SectorMesa();
        $form = $this->createForm('AppBundle\Form\SectorMesaType', $sectorMesa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $mesasMatrix = $request->get('mesas');

            $this->get(SectorManager::class)->nuevo($sectorMesa, $mesasMatrix);

            $sectorMesa->setEmpresa($this->get(EmpresaManager::class)->getEmpresaLogueada());

            $em = $this->getDoctrine()->getManager();
            $em->persist($sectorMesa);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('%entity% Creado Correctamente',
                    array('%entity%' => 'SectorMesa'),
                    'flashes')
            );

            return $this->redirectToRoute('sectormesa_show', array('id' => $sectorMesa->getId()));
        }

        return $this->render('sectormesa/new.html.twig', array(
            'sectorMesa' => $sectorMesa,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a SectorMesa entity.
     *
     */
    public function showAction(SectorMesa $sectorMesa)
    {
        $deleteForm = $this->createDeleteForm($sectorMesa);

        return $this->render('sectormesa/show.html.twig', array(
            'sectorMesa' => $sectorMesa,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing SectorMesa entity.
     *
     */
    public function editAction(Request $request, SectorMesa $sectorMesa)
    {
        $deleteForm = $this->createDeleteForm($sectorMesa);
        $editForm = $this->createForm('AppBundle\Form\SectorMesaType', $sectorMesa);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->get(SectorManager::class)->edit($sectorMesa, $request->get('mesas'));
            $em->persist($sectorMesa);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('%entity% Actualizado Correctamente',
                    array('%entity%' => 'SectorMesa'),
                    'flashes')
            );

            return $this->redirectToRoute('sectormesa_edit', array('id' => $sectorMesa->getId()));
        } else {




        }

        return $this->render('sectormesa/edit.html.twig', array(
            'sectorMesa' => $sectorMesa,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a SectorMesa entity.
     *
     */
    public function deleteAction(Request $request, SectorMesa $sectorMesa)
    {
        $form = $this->createDeleteForm($sectorMesa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($sectorMesa);
            $em->flush();
        }

        return $this->redirectToRoute('sectormesa_index');
    }

    /**
     * Creates a form to delete a SectorMesa entity.
     *
     * @param SectorMesa $sectorMesa The SectorMesa entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(SectorMesa $sectorMesa)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sectormesa_delete', array('id' => $sectorMesa->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}

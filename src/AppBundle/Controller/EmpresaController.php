<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Empresa;
use AppBundle\Form\EmpresaType;

/**
 * Empresa controller.
 *
 */
class EmpresaController extends Controller
{
    /**
     * Lists all Empresa entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $empresas = $em->getRepository('AppBundle:Empresa')->findAll();

        return $this->render('empresa/index.html.twig', array(
            'empresas' => $empresas,
        ));
    }

    /**
     * Creates a new Empresa entity.
     *
     */
    public function newAction(Request $request)
    {
        $empresa = new Empresa();
        $form = $this->createForm('AppBundle\Form\EmpresaType', $empresa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($empresa);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('%entity% Creado Correctamente',
                    array('%entity%' => 'Empresa'),
                    'flashes')
            );

            return $this->redirectToRoute('empresa_show', array('id' => $empresa->getId()));
        }

        return $this->render('empresa/new.html.twig', array(
            'empresa' => $empresa,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Empresa entity.
     *
     */
    public function showAction(Empresa $empresa)
    {
        $deleteForm = $this->createDeleteForm($empresa);

        return $this->render('empresa/show.html.twig', array(
            'empresa' => $empresa,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Empresa entity.
     *
     */
    public function editAction(Request $request, Empresa $empresa)
    {
        $deleteForm = $this->createDeleteForm($empresa);
        $editForm = $this->createForm('AppBundle\Form\EmpresaType', $empresa);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($empresa);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('%entity% Actualizado Correctamente',
                    array('%entity%' => 'Empresa'),
                    'flashes')
            );

            return $this->redirectToRoute('empresa_edit', array('id' => $empresa->getId()));
        }

        return $this->render('empresa/edit.html.twig', array(
            'empresa' => $empresa,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Empresa entity.
     *
     */
    public function deleteAction(Request $request, Empresa $empresa)
    {
        $form = $this->createDeleteForm($empresa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($empresa);
            $em->flush();
        }

        return $this->redirectToRoute('empresa_index');
    }

    /**
     * Creates a form to delete a Empresa entity.
     *
     * @param Empresa $empresa The Empresa entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Empresa $empresa)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('empresa_delete', array('id' => $empresa->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }


    /**
     * Lista las empresas asiciadas al usuario
     *
     */
    public function choiceAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $persona = $this->getUser()->getPersonaActiva();

        $empresas = $em->getRepository("AppBundle:Empresa")->getEmpresasPorPersona($persona);

        if (count($empresas) == 1) {

            $this->get("session")->set("empresaId", $empresas[0]->getId());

            return $this->redirectToRoute('app_homepage');
        }

        if (count($empresas) > 1) {
            $this->get("session")->set("empresaId", $empresas[0]->getId());

            return $this->render('empresa/choice_empresa.html.twig', array(
                'empresas' => $empresas,
                'prefix_image' => $request->getBasePath() . $this->getParameter("app.path.empresas_image")

            ));
        }


    }
}

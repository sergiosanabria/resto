<?php

namespace AppBundle\Controller;

use AppBundle\Entity\RolRoute;
use AppBundle\Entity\Route;
use AppBundle\Services\MapeoManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Rol;
use AppBundle\Form\RolType;

/**
 * Rol controller.
 *
 */
class RolController extends Controller
{
    /**
     * Lists all Rol entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $rols = $em->getRepository('AppBundle:Rol')->findAll();

        return $this->render('rol/index.html.twig', array(
            'rols' => $rols,
        ));
    }

    /**
     * Creates a new Rol entity.
     *
     */
    public function newAction(Request $request)
    {
        $rol = new Rol();
        $form = $this->createForm('AppBundle\Form\RolType', $rol);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($rol);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('%entity% Creado Correctamente',
                    array('%entity%' => 'Rol'),
                    'flashes')
            );

            return $this->redirectToRoute('rol_show', array('id' => $rol->getId()));
        }

        return $this->render('rol/new.html.twig', array(
            'rol' => $rol,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Rol entity.
     *
     */
    public function showAction(Rol $rol)
    {
        $deleteForm = $this->createDeleteForm($rol);

        return $this->render('rol/show.html.twig', array(
            'rol' => $rol,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Rol entity.
     *
     */
    public function editAction(Request $request, Rol $rol)
    {
        $deleteForm = $this->createDeleteForm($rol);
        $editForm = $this->createForm('AppBundle\Form\RolType', $rol);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($rol);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('%entity% Actualizado Correctamente',
                    array('%entity%' => 'Rol'),
                    'flashes')
            );

            return $this->redirectToRoute('rol_edit', array('id' => $rol->getId()));
        }

        return $this->render('rol/edit.html.twig', array(
            'rol' => $rol,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Rol entity.
     *
     */
    public function deleteAction(Request $request, Rol $rol)
    {
        $form = $this->createDeleteForm($rol);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($rol);
            $em->flush();
        }

        return $this->redirectToRoute('rol_index');
    }

    /**
     * Creates a form to delete a Rol entity.
     *
     * @param Rol $rol The Rol entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Rol $rol)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('rol_delete', array('id' => $rol->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Finds and displays a Rol entity.
     *
     */
    public function choiceAction()
    {
        $roles = $this->getDoctrine()->getRepository('AppBundle:Rol')->getByUsuario($this->getUser());


        if (count($roles) < 1 && $this->getUser()->getRoot()) {

            return $this->redirectToRoute('empresa_choice');
        }

        if (count($roles) == 1) {

            $this->get("session")->set("rolId", $roles[0]->getId());

            return $this->redirectToRoute('empresa_choice');
        }

        if (count($roles) > 1) {

            return $this->render(':rol:choice_rol.html.twig', array(
                'roles' => $roles,
            ));
        }



    }

    /**
     * Displays a form to edit an existing Rol entity.
     *
     */
    public function admAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        if (!$id) {
            $rol = new Rol();
        } else {
            $rol = $em->getRepository("AppBundle:Rol")->find($id);
        }

        $editForm = $this->createForm('AppBundle\Form\RolType', $rol);
        $editForm->handleRequest($request);

        $rolesRequest = $request->get('rol');

        $mapeo = $this->get(MapeoManager::class);

        $routesRol = $rol->getRoutes();


        if ($editForm->isSubmitted() && $editForm->isValid()) {

            if (!$rol->getId()) {

                foreach ($rolesRequest as $rolId) {
                    $unRolRoute = new RolRoute();
                    $unRolRoute->setRol($rol);
                    $unRolRoute->setRoute($em->find(Route::class, $rolId));
                    $rol->addRoute($unRolRoute);
                }

            } else {

                foreach ($rolesRequest as $rolId) {

                    $item = $em->find(Route::class, $rolId);
                    $routeRol = $mapeo->inCollection($routesRol, $item, 'route');

                    if (!$routeRol) {
                        $unRolRoute = new RolRoute();
                        $unRolRoute->setRol($rol);
                        $unRolRoute->setRoute($item);
                        $rol->addRoute($unRolRoute);
                    } else {
                        $routeRol->setActivo(true);

                    }
                }


                $rolesNotIn = $em->getRepository("AppBundle:RolRoute")->getRouteNotInRol($rolesRequest, $rol);

                foreach ($rolesNotIn as $item) {
                    $item->setActivo(false);
                }

            }

            $em->persist($rol);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('%entity% Actualizado Correctamente',
                    array('%entity%' => 'Rol'),
                    'flashes')
            );

            return $this->redirectToRoute('rol_edit', array('id' => $rol->getId()));
        }


        $routesDB = $em->getRepository(Route::class)->findBy(array(
            'activo' => true
        ));


        $routes = [];

        /* @var $route \AppBundle\Entity\Route */
        foreach ($routesDB as $item) {

            $routeArray = $mapeo->objToArray(['id', 'nombre', 'descripcion'], $item);

            $routeRol = $mapeo->inCollection($routesRol, $item, 'route');

            if ($routeRol && $routeRol->getActivo()) {
                $routeArray['activo'] = true;
            } else {
                $routeArray['activo'] = false;
            }

            $controllerArr = explode("\\", $item->getController());

            $controller = str_replace("Controller", "", $controllerArr[count($controllerArr) - 1]);

            $controller = str_replace("PedidoCabecera", "Pedidos", $controller);

            $routes[$controller][] = $routeArray;

        }


        return $this->render('rol/adm.html.twig', array(
            'rol' => $rol,
            'routes' => $routes,
            'form' => $editForm->createView(),
        ));
    }
}

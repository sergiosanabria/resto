<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Route;
use AppBundle\Form\RouteType;

/**
 * Route controller.
 *
 */
class RouteController extends Controller
{
    /**
     * Lists all Route entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $routes = $em->getRepository('AppBundle:Route')->findAll();

        return $this->render('route/index.html.twig', array(
            'routes' => $routes,
        ));
    }

    /**
     * Creates a new Route entity.
     *
     */
    public function newAction(Request $request)
    {
        $route = new Route();
        $form = $this->createForm('AppBundle\Form\RouteType', $route);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($route);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('%entity% Creado Correctamente',
                    array('%entity%' => 'Route'),
                    'flashes')
            );

            return $this->redirectToRoute('route_show', array('id' => $route->getId()));
        }

        return $this->render('route/new.html.twig', array(
            'route' => $route,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Route entity.
     *
     */
    public function showAction(Route $route)
    {
        $deleteForm = $this->createDeleteForm($route);

        return $this->render('route/show.html.twig', array(
            'route' => $route,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Route entity.
     *
     */
    public function editAction(Request $request, Route $route)
    {
        $editForm = $this->createForm('AppBundle\Form\RouteType', $route);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($route);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('%entity% Actualizado Correctamente',
                    array('%entity%' => 'Route'),
                    'flashes')
            );

            return $this->redirectToRoute('route_edit', array('id' => $route->getId()));
        }

        return $this->render('route/edit.html.twig', array(
            'route' => $route,
            'edit_form' => $editForm->createView(),

        ));
    }

    /**
     * Deletes a Route entity.
     *
     */
    public function deleteAction(Request $request, Route $route)
    {
        $form = $this->createDeleteForm($route);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($route);
            $em->flush();
        }

        return $this->redirectToRoute('route_index');
    }

    public function admAction(Request $request)
    {
        /** @var $router \Symfony\Component\Routing\Router */
        $router = $this->get('router');
        /** @var $collection \Symfony\Component\Routing\RouteCollection */
        $collection = $router->getRouteCollection();
        $allRoutes = $collection->all();

        $routes = array();

        $em = $this->getDoctrine()->getManager();

        /** @var $params \Symfony\Component\Routing\Route */
        foreach ($allRoutes as $route => $params) {
            $defaults = $params->getDefaults();

            if (substr($route, 0, 1) === "_") {
                continue;
            }

            if (isset($defaults['_controller'])) {

                $controllerAction = explode(':', $defaults['_controller']);
                $controller = $controllerAction[0];

                if (!isset($routes[$controller])) {
                    $routes[$controller] = array();
                }


                $ruta = $em->getRepository("AppBundle:Route")->findOneBy(array(
                    'route' => $route
                ));

                if ($ruta) {
                    $routes[$controller][] = (object)array(
                        'route' => $route,
                        'nombre' => $ruta->getNombre(),
                        'descripcion' => $ruta->getDescripcion(),
                        'id' => $ruta->getId(),
                        'activo' => $ruta->getActivo(),
                        'controller' => $controller,
                        'nuevo' => false,
                    );
                } else {

                    $ruta = new Route();

                    $nombre = ucwords(str_replace("_", " ", $route));

                    $ruta->setNombre($nombre);
                    $ruta->setController($controller);
                    $ruta->setRoute($route);

                    $em->persist($ruta);

                    $em->flush();

                    $routes[$controller][] = (object)array(
                        'route' => $route,
                        'nombre' => $nombre,
                        'descripcion' => "",
                        'id' => $ruta->getId(),
                        'activo' => '',
                        'controller' => $controller,
                        'nuevo' => true,
                    );
                }


            }
        }

        return $this->render('route/adm.html.twig', array(
            'routes' => $routes,
        ));


        $thisRoutes = isset($routes[get_class($this)]) ?
            $routes[get_class($this)] : null;


    }

    public function setActivoAction(Request $request, Route $route)
    {

        $em = $this->getDoctrine()->getManager();

        $route->setActivo(!$route->getActivo());

        $em->flush();

        return new JsonResponse('ok');
    }

    /**
     * Creates a form to delete a Route entity.
     *
     * @param Route $route The Route entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Route $route)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('route_delete', array('id' => $route->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}

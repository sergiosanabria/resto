<?php

namespace AppBundle\Controller\Rest;

use AppBundle\Services\MapeoManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProductoRestController extends ApiRestController
{


    public function getProductoAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $cliente = $em->getRepository("AppBundle:Producto")->find($request->get('id'));

        $baseURL = $this->getPathImage('app.path.productos_image', $request);

        $cliente->setImage($baseURL . $cliente->getImage());

        $vista = $this->view($cliente,
            200);

        return $this->handleView($vista);
    }

    public function getProductosAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $value = $request->get('q');
        $limit = $request->get('page_limit');

        $entities = $em->getRepository("AppBundle:Producto")->getActivos($value, $limit);

        $json = array();

        if (!count($entities)) {
            $json[] = array(
                'text' => 'No se encontraron coincidencias',
                'id' => ''
            );
        } else {

            $baseURL = $this->getPathImage('app.path.productos_image', $request);

            foreach ($entities as $entity) {

                $ingredientes = $this->get(MapeoManager::class)->getArrayData(array(
                    'id', 'nombre'
                ), $entity->getIngredientesSinRelacion());

                $json[] = array(
                    'id' => $entity->getId(),
                    'text' => $entity->getNombre(),
                    'precio' => $entity->getPrecioDeVenta(),
                    'ingredientes' => $ingredientes,
                    'image' => $entity->getImage() ? $baseURL . '/' . $entity->getImage() : false,
                );
            }
        }

        return new JsonResponse($json);
    }
}

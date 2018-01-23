<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\Ley;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends ApiController
{
    public function indexAction()
    {
        return $this->responseSuccess([]);
    }

    public function buscadorAction(Request $request)
    {
        $rama = $request->get('rama');
        $numero = $request->get('numero');
        $leyAnterior = $request->get('ley-anterior');
        $q = $request->get('q');
        $leyGeneralConsolidada = $request->get('leyGeneralConsolidada');

        switch ($leyGeneralConsolidada) {
            case 'true':
                $leyGeneralConsolidada = true;
                break;
            case 'false':
                $leyGeneralConsolidada = false;
                break;
            default:
                $leyGeneralConsolidada = null;
        }

        /** @var QueryBuilder $results */
        $results = $this->get('api.paginator')->paginate(
            $this->get('api.queries')->getBuscador($numero, $rama, $leyAnterior, $q, $leyGeneralConsolidada),
            intval($request->get('page', 1)),
            intval($request->get('rpp', 20))
        );

        return $this->responseSuccess(
            $this->get('api.mapper')->mapLeyes($results['data']),
            $results['pagination']
        );
    }
}

<?php

namespace ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class LegislacionesController extends ApiController
{
    public function indexAction(Request $request)
    {
        $results = $this->get('api.paginator')->paginate(
            $this->get('api.queries')->getLegislaciones(),
            intval($request->get('page', 1)),
            intval($request->get('rpp', 20))
        );

        return $this->responseSuccess(
            $this->get('api.mapper')->mapLegislaciones($results['data']),
            $results['pagination']
        );
    }
}

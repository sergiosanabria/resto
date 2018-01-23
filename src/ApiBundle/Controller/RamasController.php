<?php

namespace ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class RamasController extends ApiController
{
    public function indexAction(Request $request)
    {
        $results = $this->get('api.paginator')->paginate(
            $this->get('api.queries')->getRamas(),
            intval($request->get('page', 1)),
            intval($request->get('rpp', 20))
        );

        return $this->responseSuccess(
            $this->get('api.mapper')->mapRamas($results['data']),
            $results['pagination']
        );
    }
}

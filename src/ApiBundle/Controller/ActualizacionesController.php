<?php

namespace ApiBundle\Controller;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ActualizacionesController extends ApiController
{
    public function indexAction(Request $request)
    {
        $file = $this->get('updater.updater')->generateUpdate();

        $response = new BinaryFileResponse($file);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);

        return $response;
    }

    public function webAppVersionAction(Request $request)
    {
        return $this->responseSuccess([
            'version' => '1.7.9'
        ]);
    }
}

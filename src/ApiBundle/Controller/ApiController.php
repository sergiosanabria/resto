<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\Ley;
use AppBundle\Entity\Rama;
use DateTime;
use Doctrine\Common\Util\Debug;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends Controller
{
    public function responseSuccess($data, $pagination = null, $headers = [])
    {
        $content = array(
            'status' => 'success',
            'data' => $data,
        );

        if ($pagination) {
            $content['pagination'] = $pagination;
        }

        $requestHeaders = $this->get('request')->headers->get('Access-Control-Request-Headers');
        if ($requestHeaders) {
            $requestHeaders = 'Content-Type, '.$requestHeaders;
        } else {
            $requestHeaders = 'Content-Type, api-version';
        }

        $headers = array_merge(array(
            'Access-Control-Allow-Methods' => 'GET',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => $requestHeaders,
        ), $headers);

        return JsonResponse::create($content, 200, $headers);
    }
}

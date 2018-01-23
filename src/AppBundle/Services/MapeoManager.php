<?php

namespace AppBundle\Services;


use AppBundle\Entity\Direccion;
use Doctrine\ORM\EntityManager;


class MapeoManager
{

    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    public function getArrayData($array, $data)
    {

        $result = [];

        if ($data && count($data) > 0) {
            foreach ($data as $obj) {

                $result [] = $this->objToArray($array, $obj);
            }
        }


        return $result;

    }

    public function objToArray($array, $obj)
    {

        $dataItem = [];

        foreach ($array as $item) {

            $get = "get" . ucfirst($item);
            $dataItem[$item] = $obj->$get();

        }

        return $dataItem;
    }


    public function inCollection($array, $value, $attr)
    {

        $get = "get" . ucfirst($attr);

        foreach ($array as $obj) {

            if ($obj->$get() == $value) {
                return $obj;
            }
        }

        return false;

    }


}
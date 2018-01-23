<?php

namespace AppBundle\Services;


use AppBundle\Entity\Direccion;
use Doctrine\ORM\EntityManager;
use Geocoder\Plugin\PluginProvider;
use Geocoder\Query\GeocodeQuery;

class DireccionManager
{

    private $em;
    private $geocoder;

    public function __construct(EntityManager $em, PluginProvider $geocoder)
    {
        $this->em = $em;
        $this->geocoder = $geocoder;
    }


    public function getLocation($direccion)
    {

        if ($direccion) {
            $result = $this->geocoder->geocodeQuery(GeocodeQuery::create($direccion));

            return $result;

        } else {
            return [];
        }

    }


    public function setLocationDireccion(Direccion &$direccion)
    {

        $address = $direccion->getCalle() . ' ' . $direccion->getAltura() . ', ' . $direccion->getLocalidad()->getDescripcion();

        $locations = $this->getLocation($address);

        if (!$locations->isEmpty()) {

            $location = $locations->first();

            $direccion->setLatitud($location->getCoordinates()->getLatitude());
            $direccion->setLongitud($location->getCoordinates()->getLongitude());
            $direccion->setBarrio($location->getSubLocality());

        }

    }

    public function setLocationCollDireccion($direcciones)
    {

        if (count($direcciones)) {

            foreach ($direcciones as $direccione) {

                $this->setLocationDireccion($direccione);
            }
        }

    }
}
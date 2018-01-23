<?php

namespace AppBundle\Services;


use AppBundle\Entity\Mesa;
use AppBundle\Entity\SectorMesa;
use Doctrine\ORM\EntityManager;


class SectorManager
{

    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    public function nuevo(SectorMesa &$sector, $mesasMatrix)
    {

        foreach ($mesasMatrix as $indexCol => $mesas) {
            foreach ($mesas as $indexFila => $mesa) {
                $unaMesa = new Mesa();
                $unaMesa->setNombre($mesa);
                $unaMesa->setFila($indexFila);
                $unaMesa->setColumna($indexCol);
                $unaMesa->setSector($sector);
                $unaMesa->setActivo(true);

                $sector->addMesa($unaMesa);
            }
        }

    }

    public function edit(SectorMesa &$sector, $mesasMatrix)
    {
        $mesasUpdateIds = [];

        foreach ($mesasMatrix as $indexCol => $mesas) {
            foreach ($mesas as $indexFila => $mesaJson) {

                $mesa = json_decode($mesaJson);
                $unaMesa = new Mesa();
                if (!isset($mesa->creado)) {

                    $sector->addMesa($unaMesa);
                } else {
                    $unaMesa = $sector->inCollection($sector->getMesas(), $mesa->id, 'id');
                    $mesasUpdateIds [] = $unaMesa->getId();
                }

                $unaMesa->setNombre($mesa->nombre);
                $unaMesa->setFila($indexFila);
                $unaMesa->setColumna($indexCol);
                $unaMesa->setSector($sector);
                $unaMesa->setActivo(true);
            }
        }

        if (count($mesasUpdateIds)) {
//            $mesasDesactivar = $this->em->getRepository(Mesa::class)->getMesaNotInSector($mesasUpdateIds, $sector);
//
//            if (count($mesasDesactivar)) {
//                foreach ($mesasDesactivar as $unaMesa) {
//                    $unaMesa->setActivo(false);
//                }
//            }

            if (count($sector->getMesas())) {
                foreach ($sector->getMesas() as $unaMesa) {
                    if (!is_null($unaMesa->getId()) && !in_array($unaMesa->getId(), $mesasUpdateIds) && $unaMesa->getActivo()) {
                        $unaMesa->setActivo(false);
                    }
                }
            }
        }
    }


}
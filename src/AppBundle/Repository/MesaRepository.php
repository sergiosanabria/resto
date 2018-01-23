<?php

namespace AppBundle\Repository;
use AppBundle\Entity\SectorMesa;

/**
 * MesaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MesaRepository extends \Doctrine\ORM\EntityRepository
{
    public function getMesaNotInSector($ids, SectorMesa $sector)
    {

        if (is_array($ids)) {
            $ids = implode($ids, ', ');
        }

        $qb = $this->createQueryBuilder('mesa')
            ->andWhere("mesa.sector = :sector")
            ->andWhere("mesa.id NOT IN ($ids)")
            ->andWhere("mesa.activo = true")
            ->setParameter("sector", $sector);

        return $qb->getQuery()->getResult();

    }

    public function getMesaSector($ids, SectorMesa $sector)
    {


        $qb = $this->createQueryBuilder('mesa')
            ->andWhere("mesa.sector = :sector")
            ->andWhere("mesa.activo = true")
            ->setParameter("sector", $sector)
            ->orderBy('id', 'DESC')
            ->groupBy('mesa')
        ;

        return $qb->getQuery()->getResult();

    }
}
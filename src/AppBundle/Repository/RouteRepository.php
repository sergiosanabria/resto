<?php

namespace AppBundle\Repository;

/**
 * RouteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RouteRepository extends \Doctrine\ORM\EntityRepository
{

    public function getRouteDesactivado($route, $startWith = false)
    {
        $qb = $this->createQueryBuilder("route")
            ->andWhere("route.activo = false");

        if ($startWith) {
            $qb->andWhere("route.route like :route")
                ->setParameter("route", "%$route");
        } else {
            $qb->andWhere("route.route = :route")
                ->setParameter("route", $route);
        }

        return $qb->getQuery()->getResult();
    }
}

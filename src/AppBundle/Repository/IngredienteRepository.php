<?php

namespace AppBundle\Repository;

/**
 * IngredienteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class IngredienteRepository extends \Doctrine\ORM\EntityRepository
{

    public function getPorEmpresa($q, $limit)
    {
        $qb = $this->createQueryBuilder('ingrediente')
            ->andWhere("ingrediente.nombre LIKE :q")
            ->setParameter('q', "%$q%")
            ->andWhere('ingrediente.activo = true')
            ->orderBy('ingrediente.nombre')
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }
}

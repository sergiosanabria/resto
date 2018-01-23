<?php

namespace ApiBundle\Services;

use AppBundle\Entity\Anexo;
use AppBundle\Entity\Descriptor;
use AppBundle\Entity\DescriptorLey;
use AppBundle\Entity\Identificador;
use AppBundle\Entity\IdentificadorLey;
use AppBundle\Entity\Ley;
use AppBundle\Entity\Rama;
use AppBundle\Entity\TipoLey;
use DateTime;
use Doctrine\Common\Util\Debug;
use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\ORM\QueryBuilder;

class Paginator
{
    /**
     * Pagina los resultados para la API
     *
     * Si $rpp es 0, se devuelven todos los resultados en una sola página.
     *
     * @param QueryBuilder $qb
     * @param int $page
     * @param int $rpp
     * @return array
     */
    public function paginate(QueryBuilder $qb, $page = 1, $rpp = 20)
    {
        $alias = $qb->select()->getRootAliases()[0];
        $selects = $qb->getDQLPart('select');
        $orderBy = $qb->getDQLPart('orderBy');
        $qb->resetDQLPart('orderBy');
        $qb->resetDQLPart('select');

        $qb->select('count(' . $alias . '.id)');

        $total = intval($qb->getQuery()->getSingleResult()[1]);

        $pages = 1;
        $qb->resetDQLPart('select');
        foreach ($selects as $select) {
            $qb->addSelect($select->getParts()[0]);
        }
        foreach ($orderBy as $ob) {
            $ob = explode(' ', $ob->getParts()[0], 2);
            $qb->addOrderBy($ob[0], $ob[1]);
        }

        if ($rpp > 0) {
            $pages = round(($total + $rpp - 1) / $rpp);
            $qb->setFirstResult(($page - 1) * $rpp);
            $qb->setMaxResults($rpp);
        }

        $results = $qb->getQuery()->getResult();

        if ($rpp > 0) {
            $from = (($page - 1) * $rpp) + 1;
            $to = (($page - 1) * $rpp) + count($results);
        } else {
            $rpp = count($results);
            $from = 1;
            $to = count($results);
        }

        return array(
            'data' => $results,
            'pagination' => array(
                'total' => $total,
                'perPage' => $rpp,
                'currentPage' => $page,
                'count' => count($results),
                'lastPage' => $pages,
                'from' => $from,
                'to' => $to,
            ),
        );
    }
}
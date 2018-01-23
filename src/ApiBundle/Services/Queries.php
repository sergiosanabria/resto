<?php

namespace ApiBundle\Services;

use AppBundle\Entity\Ley;
use AppBundle\Entity\Rama;
use Doctrine\ORM\EntityManager;
use WebBundle\Entity\MenuDocumento;

class Queries
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * Queries constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getLegislaciones()
    {
        return $this->em->getRepository(MenuDocumento::class)
            ->createQueryBuilder('md')
            ->where('md.activo = 1')
            ->andWhere('md.padre IS NULL')
            ->orderBy('md.orden', 'asc');
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getRamas()
    {
        return $this->em->getRepository(Rama::class)
            ->createQueryBuilder('r')
            ->where('r.activo = 1');
    }

    /**
     * @param string $numero
     * @param string $rama
     * @param string $leyAnterior
     * @param string $q
     * @param bool|null $leyGeneralConsolidada
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getBuscador($numero = '', $rama = '', $leyAnterior = '', $q = '', $leyGeneralConsolidada = null)
    {
        $criteria = array(
            'q' => $q,
            'numero' => $numero,
            'rama' => $rama,
            'leyAnterior' => $leyAnterior,
        );

        $query = $this->em->getRepository(Ley::class)->findLeyes($criteria, true);

        if ($leyGeneralConsolidada !== null) {
            $query->andWhere('l.leyGeneralConsolidada = :val');
            $query->setParameter('val', $leyGeneralConsolidada);
        }

        return $query;
    }
}
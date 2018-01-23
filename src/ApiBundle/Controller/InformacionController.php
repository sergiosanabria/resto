<?php

namespace ApiBundle\Controller;

use WebBundle\Entity\AnalisisDocumental;
use WebBundle\Entity\AnalisisEpistemologico;
use WebBundle\Entity\AnalisisNormativo;
use WebBundle\Entity\Antecedente;
use WebBundle\Entity\InformeGestion;
use WebBundle\Entity\Prologo;

class InformacionController extends ApiController
{
    public function indexAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $prologo = $em->getRepository(Prologo::class)->findBy(array('activo' => true));
        $analisisDocumentales = $em->getRepository(AnalisisDocumental::class)->findBy(array('activo' => true));
        $analisisEpistemologicos = $em->getRepository(AnalisisEpistemologico::class)->findBy(array('activo' => true));
        $analisisNormativos = $em->getRepository(AnalisisNormativo::class)->findBy(array('activo' => true));
        $antecedentes = $em->getRepository(Antecedente::class)->findBy(array('activo' => true));
        $informesGestion = $em->getRepository(InformeGestion::class)->findBy(array('activo' => true));

        return $this->responseSuccess(
            $this->get('api.mapper')->mapInformaciones(
                $prologo,
                $analisisDocumentales,
                $analisisEpistemologicos,
                $analisisNormativos,
                $antecedentes,
                $informesGestion
            )
        );
    }
}

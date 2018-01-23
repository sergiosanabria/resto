<?php

namespace ApiBundle\Services;

use AppBundle\Entity\AdhesionLeyesNacionales;
use AppBundle\Entity\Anexo;
use AppBundle\Entity\CartaOrganica;
use AppBundle\Entity\Codigo;
use AppBundle\Entity\Constitucion;
use AppBundle\Entity\Descriptor;
use AppBundle\Entity\DescriptorLey;
use AppBundle\Entity\Identificador;
use AppBundle\Entity\IdentificadorLey;
use AppBundle\Entity\Ley;
use AppBundle\Entity\LeyConsolidacion;
use AppBundle\Entity\LeyOrganica;
use AppBundle\Entity\Rama;
use AppBundle\Entity\TipoLey;
use DateTime;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Asset\Packages;
use Vich\UploaderBundle\Mapping\PropertyMappingFactory;
use WebBundle\Entity\MenuDocumento;

class Mapper
{
    /**
     * @var string
     */
    protected $modoDeArchivos = 'web';

    /**
     * @var Packages
     */
    protected $assetsPackages;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var PropertyMappingFactory
     */
    protected $vichUploader;

    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(
        Packages $assetsPackages,
        Router $router,
        PropertyMappingFactory $vichUploader,
        EntityManager $em
    )
    {
        $this->assetsPackages = $assetsPackages;
        $this->router = $router;
        $this->vichUploader = $vichUploader;
        $this->em = $em;
    }

    public function setModoDeArchivos($modoDeArchivos)
    {
        $this->modoDeArchivos = $modoDeArchivos;
    }

    /**
     * @param array $ramas
     * @return array
     */
    public function mapRamas(array $ramas)
    {
        return array_map(array($this, 'mapRama'), $ramas);
    }

    /**
     * @param DateTime $dateTime
     * @return string
     */
    public function mapDateTime(DateTime $dateTime)
    {
        return $dateTime->format(DateTime::ATOM);
    }

    /**
     * @param Rama $rama
     * @return array
     */
    public function mapRama(Rama $rama)
    {
        return array(
            'id' => $rama->getId(),
            'titulo' => $rama->getTitulo(),
            'numeroRomano' => $rama->getNumeroRomano(),
            'soloTitulo' => $rama->getSoloTitulo(),
            'color' => $rama->getColor(),
            'colorLetra' => $rama->getColorLetra(),
            'especial' => $rama->getEspecial(),
        );
    }

    /**
     * @param array $leyes
     */
    public function mapLeyes(array $leyes)
    {
        return array_map(array($this, 'mapLey'), $leyes);
    }

    /**
     * @param Ley $ley
     * @return array
     */
    public function mapLey(Ley $ley)
    {
        $titulo = explode(' ', $ley->getRama()->getTitulo())[0];
        $tituloLey = 'LEY ' . $titulo . ' - Nº ' . $ley->getTitulo();

        return array(
            'id' => $ley->getId(),
            'titulo' => $ley->getTitulo(),
            'leyString' => $tituloLey,
            'denominacionAnterior' => $ley->getDenominacionAnterior(),
            'leyAnterior' => $ley->getLeyAnterior(),
            'anioAnterior' => $ley->getAnioAnterior(),
            'tema' => $ley->getTema(),
            'palabrasClaves' => $ley->getPalabrasClaves(),
            'descriptores' => $this->mapDescriptores($ley->getDescriptores()->toArray()),
            'identificadores' => $this->mapIdentificadores($ley->getIdentificadores()->toArray()),
            'fechaPublicacion' => $this->mapDateTime($ley->getFechaPublicacion()),
            'rama' => $this->mapRama($ley->getRama()),
            'tipoLey' => $this->mapTipoLey($ley->getTipoLey()),
            'anexoLey' => $this->mapAnexoLey($ley->getAnexoLey()->toArray()),
            'leyGeneralConsolidada' => $ley->getLeyGeneralConsolidada(),
            'archivo' => $this->mapArchivo($ley->getArchivo()),
            'archivoFile' => $ley->getArchivoFile(),
        );
    }

    /**
     * @param array $descriptores
     * @return array
     */
    public function mapDescriptores(array $descriptores)
    {
        return array_map(array($this, 'mapDescriptorLey'), $descriptores);
    }

    /**
     * @param DescriptorLey $descriptorLey
     * @return array
     */
    public function mapDescriptorLey(DescriptorLey $descriptorLey)
    {
        return $this->mapDescriptor($descriptorLey->getDescriptor());
    }

    /**
     * @param Descriptor $descriptor
     * @return array
     */
    public function mapDescriptor(Descriptor $descriptor)
    {
        return array(
            'id' => $descriptor->getId(),
            'nombre' => $descriptor->getNombre(),
            'descripcion' => $descriptor->getDescripcion(),
            'slug' => $descriptor->getSlug(),
        );
    }

    /**
     * @param array $identificadores
     * @return array
     */
    public function mapIdentificadores(array $identificadores)
    {
        return array_map(array($this, 'mapIdentificadorLey'), $identificadores);
    }

    /**
     * @param IdentificadorLey $identificadorLey
     * @return array
     */
    public function mapIdentificadorLey(IdentificadorLey $identificadorLey)
    {
        return $this->mapIdentificador($identificadorLey->getIdentificador());
    }

    /**
     * @param Identificador $identificador
     * @return array
     */
    public function mapIdentificador(Identificador $identificador)
    {
        return array(
            'id' => $identificador->getId(),
            'nombre' => $identificador->getNombre(),
            'descripcion' => $identificador->getDescripcion(),
            'slug' => $identificador->getSlug(),
        );
    }

    /**
     * @param TipoLey $tipoLey
     * @return array
     */
    public function mapTipoLey(TipoLey $tipoLey)
    {
        return array(
            'id' => $tipoLey->getId(),
            'descripcion' => $tipoLey->getDescripcion(),
        );
    }

    /**
     * @param array $anexoLey
     * @return array
     */
    public function mapAnexoLey(array $anexoLey)
    {
        return array_map(array($this, 'mapAnexo'), $anexoLey);
    }

    /**
     * @param Anexo $anexo
     * @return array
     */
    public function mapAnexo(Anexo $anexo)
    {
        return array(
            'id' => $anexo->getId(),
            'fecha' => $this->mapDateTime($anexo->getFecha()),
            'titulo' => $anexo->getTitulo(),
            // 'ley' => $this->mapLey($anexo->getLey()),
            'archivo' => $this->mapArchivo($anexo->getArchivo()),
            'archivoFile' => $anexo->getArchivoFile(),
        );
    }

    public function mapArchivo($archivo, $path = 'leyes')
    {
        if ($path) {
            $path .= '/';
        }

        $path = $this->assetsPackages->getUrl('uploads/documentos/' . $path . $archivo);

        if ($this->modoDeArchivos == 'desktop') {
            $path = preg_replace('~^.+uploads\/~', '', $path);
        }

        return $path;
    }

    public function mapLeyesConsolidacion(array $leyesConsolidacion)
    {
        return array_map(array($this, 'mapLeyConsolidacion'), $leyesConsolidacion);
    }

    public function mapLeyConsolidacion(LeyConsolidacion $leyConsolidacion)
    {
        return [
            'id' => $leyConsolidacion->getId(),
            'anexo' => $leyConsolidacion->getAnexo(),
            'descripcion' => $leyConsolidacion->getDescripcion(),
            'archivo' => $this->mapArchivo($leyConsolidacion->getArchivo(), 'consolidacion'),
            'archivoFile' => $leyConsolidacion->getArchivoFile(),
        ];
    }

    public function mapAdhesionesLeyesNacionales(array $adhesionesLeyesNacionales)
    {
        return array_map(array($this, 'mapAdhesionLeyesNacionales'), $adhesionesLeyesNacionales);
    }

    public function mapAdhesionLeyesNacionales(AdhesionLeyesNacionales $adhesionLeyesNacionales)
    {
        return [
            'id' => $adhesionLeyesNacionales->getId(),
            'numeroLeyDecretoLey' => $adhesionLeyesNacionales->getNumeroLeyDecretoLey(),
            'tema' => $adhesionLeyesNacionales->getTema(),
            'ley' => $this->mapLey($adhesionLeyesNacionales->getLey()),
            'numero' => $adhesionLeyesNacionales->getNumero(),
            'anexoLey' => $this->mapAnexoLey($adhesionLeyesNacionales->getAnexoLeyAdhesion()),
        ];
    }

    public function mapCartasOrganicas(array $cartasOrganicas)
    {
        return array_map(array($this, 'mapCartaOrganica'), $cartasOrganicas);
    }

    public function mapCartaOrganica(CartaOrganica $cartaOrganica)
    {
        return [
            'id' => $cartaOrganica->getId(),
            'titulo' => $cartaOrganica->getTitulo(),
            'archivo' => $this->mapArchivo($cartaOrganica->getArchivo(), 'cartas_organicas'),
            'archivoFile' => $cartaOrganica->getArchivoFile(),
        ];
    }

    public function mapCodigos(array $codigos)
    {
        return array_map(array($this, 'mapCodigo'), $codigos);
    }

    public function mapCodigo(Codigo $codigo)
    {
        return [
            'id' => $codigo->getId(),
            'titulo' => $codigo->getTitulo(),
            'orden' => $codigo->getOrden(),
            'archivo' => $this->mapArchivo($codigo->getArchivo(), 'codigos'),
            'archivoFile' => $codigo->getArchivoFile(),
        ];
    }

    public function mapLeyesOrganicas(array $leyesOrganicas)
    {
        return array_map(array($this, 'mapLeyOrganica'), $leyesOrganicas);
    }

    public function mapLeyOrganica(LeyOrganica $leyOrganica)
    {
        return [
            'id' => $leyOrganica->getId(),
            'titulo' => $leyOrganica->getTitulo(),
            'tema' => $leyOrganica->getTema(),
            'orden' => $leyOrganica->getOrden(),
            'archivo' => $this->mapArchivo($leyOrganica->getArchivo(), 'leyes_organicas'),
            'archivoFile' => $leyOrganica->getArchivoFile(),
            'anexoLey' => $this->mapAnexoLey($leyOrganica->getAnexoLeyOrganica()),
        ];
    }

    public function mapConstituciones(array $constituciones)
    {
        return array_map(array($this, 'mapConstitucion'), $constituciones);
    }

    public function mapConstitucion(Constitucion $constitucion)
    {
        return [
            'id' => $constitucion->getId(),
            'titulo' => $constitucion->getTitulo(),
            'archivo' => $this->mapArchivo($constitucion->getArchivo(), ''),
            'archivoFile' => $constitucion->getArchivoFile(),
        ];
    }

    public function mapLegislaciones(array $legislaciones)
    {
        return array_map(array($this, 'mapLegislacion'), $legislaciones);
    }

    public function mapLegislacion(MenuDocumento $legislacion)
    {
        $ret = [
            'id' => $legislacion->getId(),
            'titulo' => $legislacion->getTitulo(),
            'descripcion' => $legislacion->getDescripcion(),
            'entity' => $legislacion->getEntity(),
            'orden' => $legislacion->getOrden(),
        ];

        if ($legislacion->getHijos()->count()) {
            $ret['hijos'] = $this->mapLegislaciones($legislacion->getHijos()->toArray());
        }

        if ($legislacion->getEntity()) {
            $ret['documentos'] = $this->getDocumentosDeLegislacion($legislacion);
        }
        return $ret;
    }

    private function getDocumentosDeLegislacion(MenuDocumento $legislacion)
    {
        $entity = $legislacion->getEntity();

        $fields = $this->em->getClassMetadata($entity)->getFieldNames();

        $query = $this->em->getRepository($entity)
            ->createQueryBuilder('dg')
            ->where('dg.activo = 1');

        if (in_array('orden', $fields)) {
            $query->orderBy('dg.orden', 'asc');
        }

        $results = $query->getQuery()->getResult();

        if ($legislacion->getEntity() == 'AppBundle:LeyConTextoActualizado') {

            $ramas = array();
            foreach ($results as $result) {
                $rama = $result->getLey()->getRama();
                if (!array_key_exists($rama->getId(), $ramas)) {
                    $ramas[$rama->getId()] = array(
                        'rama' => $rama,
                        'documentos' => array(),
                    );
                }
                $ramas[$rama->getId()]['documentos'][] = $result;
            }

            foreach ($ramas as $ramaId => $results) {
                $ramas[$ramaId]['rama'] = $this->mapRama($ramas[$ramaId]['rama']);
                $ramas[$ramaId]['documentos'] = $this->mapDocumentosGenericos($results['documentos'], $fields);
            }
            return array_values($ramas);
        } else {
            return $this->mapDocumentosGenericos($results, $fields);
        }

    }


    public function mapDocumentosGenericos(array $dgs, array $fields)
    {
        return array_map(function ($dg) use ($fields) {
            return $this->mapDocumentoGenerico($dg, $fields);
        }, $dgs);
    }

    public function mapDocumentoGenerico($dg, array $fields)
    {
        $fields = array_filter($fields, function ($field) {
            return !in_array($field, ['activo', 'fechaCreacion', 'fechaActualizacion', 'orden']);
        });

        switch (get_class($dg)) {
            case 'AppBundle\Entity\LeyConTextoActualizado':
                $fields[] = 'ley';
                break;
            case 'AppBundle\Entity\AdhesionLeyesNacionales':
                $fields[] = 'ley';
                $fields[] = 'anexoLeyAdhesion';
                break;
            case 'AppBundle\Entity\LeyOrganica':
                $fields[] = 'anexoLeyOrganica';
                break;
        }


        $ret = [];
        foreach ($fields as $field) {
            $getter = 'get' . ucfirst($field);
            $val = $dg->{$getter}();
            switch ($field) {
                case 'archivo':
                    $map = $this->vichUploader->fromObject($dg);
                    $path = $map[0]->getMappingName();

                    $ret[$field] = $this->mapArchivo($val, $path);
                    break;
                case 'ley':
                    $ret[$field] = $this->mapLey($val);
                    break;
                case 'anexoLeyAdhesion':
                    $ret['anexoLey'] = $this->mapAnexoLey($val->toArray());
                    break;
                case 'anexoLeyOrganica':
                    $ret['anexoLey'] = $this->mapAnexoLey($val->toArray());
                    break;
                default:
                    $ret[$field] = $val;
            }
        }
        return $ret;
    }

    /**
     * @param $prologo
     * @param $analisisDocumentales
     * @param $analisisEpistemologicos
     * @param $analisisNormativos
     * @param $antecedentes
     * @param $informesGestion
     * @return array
     */
    public function mapInformaciones($prologo, $analisisDocumentales, $analisisEpistemologicos, $analisisNormativos, $antecedentes, $informesGestion)
    {
        return [
            'prologo' => $this->mapDocuemntosInformaciones($prologo),
            'analisisDocumentales' => $this->mapDocuemntosInformaciones($analisisDocumentales),
            'analisisEpistemologicos' => $this->mapDocuemntosInformaciones($analisisEpistemologicos),
            'analisisNormativos' => $this->mapDocuemntosInformaciones($analisisNormativos),
            'antecedentes' => $this->mapDocuemntosInformaciones($antecedentes),
            'informesGestion' => $this->mapDocuemntosInformaciones($informesGestion),
        ];
    }

    private function mapDocuemntosInformaciones($docs) {
        return array_map(function ($doc) {
            return $this->mapDocumentoInformaciones($doc);
        }, $docs);
    }

    /**
     * @param $doc
     * @return array
     */
    private function mapDocumentoInformaciones($doc)
    {
        return [
            'titulo' => $doc->getTitulo(),
            'cuerpo' => $doc->getCuerpo(),
            'icono' => method_exists($doc, 'getIcono') ? $doc->getIcono() : null,
        ];
    }

}
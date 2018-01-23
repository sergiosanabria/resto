<?php

namespace ApiBundle\Services;

use AppBundle\Entity\Rama;
use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityManager;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use WebBundle\Entity\AnalisisDocumental;
use WebBundle\Entity\AnalisisEpistemologico;
use WebBundle\Entity\AnalisisNormativo;
use WebBundle\Entity\Antecedente;
use WebBundle\Entity\InformeGestion;
use WebBundle\Entity\MenuDocumento;
use WebBundle\Entity\Prologo;
use ZipArchive;

class Updater
{
    /**
     * @var string
     */
    protected $rootDir;

    /**
     * @var string
     */
    protected $webDir;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Mapper
     */
    protected $mapper;

    /**
     * @var Queries
     */
    protected $queries;

    /**
     * Updater constructor.
     * @param $rootDir
     * @param EntityManager $entityManager
     * @param Mapper $mapper
     */
    public function __construct($rootDir, EntityManager $entityManager, Mapper $mapper, Queries $queries)
    {
        set_time_limit(0);
        ini_set('memory_limit', '2G');

        $this->rootDir = $rootDir;
        $this->webDir = realpath($this->rootDir . '/../web');

        $this->entityManager = $entityManager;
        $this->queries = $queries;
        $this->mapper = $mapper;
        $this->mapper->setModoDeArchivos('desktop');
    }

    public function generateUpdate($force = false)
    {
        return $this->generateZip($force);
    }

    /**
     * @return string
     */
    private function generateTempDir()
    {
        $tempfile = tempnam(sys_get_temp_dir(), 'djm-');
        if (file_exists($tempfile)) {
            unlink($tempfile);
        }
        $tempfile = '/tmp/djm-';

        if (!is_dir($tempfile)) {
            mkdir($tempfile);
        }
        if (is_dir($tempfile)) {
            return $tempfile;
        }
        return null;
    }

    private function generateZip($force)
    {
        $tempDir = $this->generateTempDir();

        $zip = new ZipArchive();
        $baseFileName = '/archivo_' . date('Ymd') . '.djm';
        $zipFileName = $tempDir . $baseFileName;
        $destDir = $this->webDir . '/uploads/updates';
        $destFile = $destDir . $baseFileName;

        if (!is_dir($destDir)) {
            mkdir($destDir, 0777, true);
        }

        if (!$force && file_exists($destFile)) {
            return $destFile;
        }

        if (!$zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            throw new \Exception('Cannot open ' . $zipFileName);
        }

        $rootPath = $this->webDir . '/uploads/documentos';

        /** @var SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);

                $zip->addFile($filePath, 'documentos/' . $relativePath);
            }
        }

        $buscador = $this->getBuscador();
        $zip->addFromString('buscador.json', json_encode($buscador));

        $ramas = $this->getRamas();
        $zip->addFromString('ramas.json', json_encode($ramas));

        $legislaciones = $this->getLegislaciones();
        $zip->addFromString('legislaciones.json', json_encode($legislaciones));

        $informacion = $this->getInformacion();
        $zip->addFromString('informacion.json', json_encode($informacion));

        $version = ['version' => date('YmdHis')];
        $zip->addFromString('version.json', json_encode($version));

        $zip->close();
        if (!is_dir($destDir)) {
            mkdir($destDir);
        }

        rename($zipFileName, $destFile);

        @rmdir($tempDir);

        return $destFile;
    }

    /**
     * @return array
     */
    protected function getRamas()
    {
        return $this->mapper->mapRamas(
            $this->queries->getRamas()->getQuery()->getResult()
        );
    }

    /**
     * @return array
     */
    protected function getLegislaciones()
    {
        return $this->mapper->mapLegislaciones(
            $this->queries->getLegislaciones()->getQuery()->getResult()
        );
    }

    /**
     * @return array
     */
    protected function getBuscador()
    {
        return $this->mapper->mapLeyes(
            $this->queries->getBuscador()->getQuery()->getResult()
        );
    }

    /**
     * @return array
     */
    protected function getInformacion()
    {
        $em = $this->entityManager;

        $prologo = $em->getRepository(Prologo::class)->findBy(array('activo' => true));
        $analisisDocumentales = $em->getRepository(AnalisisDocumental::class)->findBy(array('activo' => true));
        $analisisEpistemologicos = $em->getRepository(AnalisisEpistemologico::class)->findBy(array('activo' => true));
        $analisisNormativos = $em->getRepository(AnalisisNormativo::class)->findBy(array('activo' => true));
        $antecedentes = $em->getRepository(Antecedente::class)->findBy(array('activo' => true));
        $informesGestion = $em->getRepository(InformeGestion::class)->findBy(array('activo' => true));

        return $this->mapper->mapInformaciones(
            $prologo,
            $analisisDocumentales,
            $analisisEpistemologicos,
            $analisisNormativos,
            $antecedentes,
            $informesGestion
        );
    }
}

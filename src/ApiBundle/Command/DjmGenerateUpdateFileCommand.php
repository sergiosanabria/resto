<?php

namespace ApiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DjmGenerateUpdateFileCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('djm:generate-update-file')
            ->setDescription("Genera un archivo de actualización del digesto.\nSi el archivo ya se generó con la fecha de hoy, no hace nada.")
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Fuerza la generación aun si el archivo de hoy existe');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $force = $input->getOption('force');

        $dir = $this->getContainer()->get('kernel')->getRootDir().'/../web/uploads/updates';

        if (is_dir($dir)) {
            $files = scandir($dir);

            foreach ($files as $file) {
                if (in_array($file, ['.', '..'])) {
                    continue;
                }

                if (preg_match('~^archivo_(\d+)\.djm$~', $file, $matches)) {
                    if ($matches[1] < date('Ymd')) {
                        unlink($dir.'/'.$file);
                    }
                }
            }
        }

        $file = $this->getContainer()->get('updater.updater')->generateUpdate($force);

        $output->writeln('File is: <comment>' . $file . '</comment>');
    }

}

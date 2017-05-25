<?php

namespace BIT\TYPO3\Install\Command;

use RandomLib\Factory;
use SecurityLib\Strength;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessUtils;

/**
 * @author Christoph Bessei
 */
class ActivateSystemExtensionsCommand extends \Symfony\Component\Console\Command\Command
{
    protected static $extensions = [
        'belog',
        'beuser',
        'context_help',
        'fluid_styled_content',
        'info',
        'lowlevel',
        'reports',
        'rsaauth',
        'rte_ckeditor',
        'setup',
        't3editor',
        'tstemplate',
    ];

    protected function configure()
    {
        parent::configure();
        $this->setName('typo3:activate')->setDescription('Activate TYPO3 system extensions');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->setFormatter(new OutputFormatter(true));

        $rootDir = rtrim(dirname(__DIR__, 3), '/');
        $typo3cms = $rootDir . '/vendor/bin/typo3cms';

        $command = $typo3cms . ' extension:activate';

        foreach (static::$extensions as $extension) {
            // Do not escape extension since extension keys can't contain special characters
            $process = new Process($command . ' ' . $extension);
            $exitCode = $process->run();
            if (0 !== $exitCode) {
                throw new \RuntimeException("Couldn't active extension " . $extension);
            }

            $output->writeln('Successfully activated ' . $extension);
        }

        return 0;
    }
}

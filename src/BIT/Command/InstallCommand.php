<?php

namespace BIT\TYPO3\Install\Command;

use RandomLib\Factory;
use SecurityLib\Strength;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessUtils;

/**
 * @author Christoph Bessei
 */
class InstallCommand extends \Symfony\Component\Console\Command\Command
{
    protected function configure()
    {
        parent::configure();
        $this->setName('typo3:install')->setDescription('Install TYPO3');

        $this->addArgument('database-name', InputArgument::REQUIRED, 'The database name');
        $this->addArgument('database-username', InputArgument::REQUIRED, 'The database username');
        $this->addArgument('database-password', InputArgument::REQUIRED, 'The database password');
        $this->addArgument('site-name', InputArgument::REQUIRED, 'TYPO3 site name');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->setFormatter(new OutputFormatter(true));

        $password = $this->generatePassword();

        $rootDir = rtrim(dirname(__DIR__, 3), '/');
        $typo3cms = $rootDir . '/vendor/bin/typo3cms';

        $command = $typo3cms . ' install:setup';
        $options = [
            '--use-existing-database'  => '',
            '--non-interactive'        => '',
            '--database-name'          => $input->getArgument('database-name'),
            '--database-user-name'     => $input->getArgument('database-username'),
            '--database-user-password' => $input->getArgument('database-password'),
            '--admin-user-name'        => 'admin',
            '--admin-password'         => $password,
            '--site-name'              => $input->getArgument('site-name'),
        ];

        $command .= ' ' . $this->buildCommandOptions($options);

        $process = new Process($command);
        $exitCode = $process->run(function ($type, $buffer) use ($output) {
            $output->write($buffer);
        });

        if (0 === $exitCode) {
            $output->writeln('');
            $output->writeln('<info>Admin password set to ' . $password . '</info>');
        }
        return $exitCode;
    }

    /**
     * @return string
     * @throws \RandomLib\RuntimeException
     */
    protected function generatePassword()
    {
        $factory = new Factory();
        $generator = $factory->getGenerator(new Strength(Strength::MEDIUM));
        return $generator->generateString(10);
    }

    /**
     * @param array $options
     * @return string
     */
    protected function buildCommandOptions(array $options)
    {
        $commandLine = '';
        foreach ($options as $name => $option) {
            $commandLine .= ' ';
            if (empty($option)) {
                $commandLine .= $name;
            } else {
                $commandLine .= $name . '=' . ProcessUtils::escapeArgument($option);
            }
            $commandLine .= ' ';
        }
        return $commandLine;
    }
}

#!/usr/bin/env php
<?php
/**
 * Simple wrapper for vendor/bin/typo3cms install:setup
 *
 * @author Christoph Bessei
 */
require_once __DIR__ . '/../vendor/autoload.php';

$application = new \Symfony\Component\Console\Application();

$application->add(new \BIT\TYPO3\Install\Command\InstallCommand());
$application->add(new \BIT\TYPO3\Install\Command\ActivateSystemExtensionsCommand());

$application->run();
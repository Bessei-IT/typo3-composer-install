<?php
/**
 * @author Christoph Bessei
 */

namespace BIT\TYPO3\Install\Composer\Scripts;

use BIT\TYPO3\Install\Composer\Utility;
use Composer\Script\Event;
use MongoDB\BSON\UTCDateTime;
use SecurityLib\Util;
use Symfony\Component\Process\Process;
use TYPO3\Flow\Utility\Files;

/**
 * Execute update/install recursively in defined folders
 *
 * Class Recursive
 * @package BIT\TYPO3\Install\Composer\Scripts
 */
class Recursive
{
    public static function install(Event $event)
    {
        static::executeCommand($event, 'install');
    }

    public static function update(Event $event)
    {
        static::executeCommand($event, 'update');
    }

    /**
     * @param \Composer\Script\Event $event
     * @param string $command
     * @throws \Symfony\Component\Process\Exception\ProcessFailedException
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     */
    protected static function executeCommand(Event $event, string $command)
    {
        $composerRoot = Utility::getAbsoluteRootPath();
        foreach (static::getRootFolder($event) as $folder) {
            // --working-dir
            $folder = Files::concatenatePaths([$composerRoot, $folder]);
            if (is_dir($folder)) {
                $process = new Process('composer ' . $command, $folder);
                $process->mustRun(
                    function ($type, $buffer) use ($event) {
                        $event->getIO()->write($buffer);
                    }
                );
            }
        }
    }

    /**
     * @param \Composer\Script\Event $event
     * @return array
     */
    protected static function getRootFolder(Event $event)
    {
        $config = Utility::getExtras($event, 'bit/composer-recursive');
        if (isset($config['root-folder']) && is_array($config['root-folder'])) {
            return $config['root-folder'];
        }
        return [];
    }
}

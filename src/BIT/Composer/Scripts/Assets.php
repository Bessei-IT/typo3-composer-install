<?php
/**
 * @author Christoph Bessei
 */

namespace BIT\TYPO3\Install\Composer\Scripts;

use BIT\TYPO3\Install\Composer\Utility;
use Composer\Script\Event;
use Symfony\Component\Process\Process;

class Assets
{
    public static function build(Event $event)
    {
        $composerRoot = Utility::getAbsoluteRootPath();
        $assetsExtra = Utility::getExtras($event, 'bit/assets');
        if (isset($assetsExtra['folders']) && is_array($assetsExtra['folders'])) {
            foreach ($assetsExtra['folders'] as $folder) {
                $absPath = $composerRoot . DIRECTORY_SEPARATOR . trim($folder, '/');
                $process = new Process('npm install && grunt dist', $absPath);
                $process->mustRun(
                    function ($type, $buffer) use ($event) {
                        $event->getIO()->write($buffer);
                    }
                );

            }
        }
    }
}

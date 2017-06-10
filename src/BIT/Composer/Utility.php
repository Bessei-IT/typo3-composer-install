<?php
/**
 * @author Christoph Bessei
 */

namespace BIT\TYPO3\Install\Composer;

use Composer\Script\Event;

class Utility
{
    /**
     * Get absolute composer root path, WITHOUT trailing slash
     *
     * @return string
     */
    public static function getAbsoluteRootPath()
    {
        return rtrim(dirname(__DIR__, 3) . '/');
    }

    public static function getExtras(Event $event, string $key)
    {
        $extras = $event->getComposer()->getPackage()->getExtra();
        if (isset($extras[$key])) {
            return $extras[$key];
        }

        return [];
    }
}

<?php
/**
 * @author Christoph Bessei
 */

namespace BIT\TYPO3\Install\Composer\Scripts;

use BIT\TYPO3\Install\Composer\Utility;
use Composer\Script\Event;
use Composer\Util\Filesystem;
use TYPO3\Flow\Utility\Files;

/**
 *
 * Class CreateProject
 * @package BIT\TYPO3\Install\Composer\Scripts
 */
class CreateProject
{
    protected static $composerRoot;
    protected static $templates;

    public static function init(Event $event)
    {
        static::$composerRoot = Utility::getAbsoluteRootPath();
        static::$templates = Files::concatenatePaths([static::$composerRoot, 'resources/createProjectTemplates']);
        static::initGitignore();
        static::removeCreateProjectTemplates();
    }

    /**
     * Init .gitignore files
     */
    protected static function initGitignore()
    {
        // Key: Template path relative to createProjectTemplates
        // Value: Target path relative to composer root
        $gitignoreFiles = [
            '/.root_gitignore' => '/.gitignore',
        ];

        foreach ($gitignoreFiles as $templatePath => $targetPath) {
            $fullTemplatePath = Files::concatenatePaths([static::$templates, $templatePath]);
            if (is_readable($fullTemplatePath)) {
                rename($fullTemplatePath, Files::concatenatePaths([static::$composerRoot, $targetPath]));
            }
        }
    }

    /**
     *
     */
    protected static function removeCreateProjectTemplates()
    {
        if (is_readable(static::$templates)) {
            $fs = new Filesystem();
            $fs->remove(static::$templates);
        }
    }
}

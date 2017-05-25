<?php
/**
 * @author Christoph Bessei
 * @version
 */

(function () {
    // We try to follow PHP PDS (http://php-pds.com/) so ROOT_DIR/config/ should contain all configurations.
    $appContext = \TYPO3\CMS\Core\Utility\GeneralUtility::getApplicationContext();
    $configDir = __DIR__;
    $configFileBaseName = $configDir . '/Config';

    // Load config files of current TYPO3 context.

    // 1. Load default config ConfigDefault.php
    $defaultConfigFile = $configFileBaseName . 'Default.php';
    if (is_readable($defaultConfigFile)) {
        require_once $defaultConfigFile;
    }

    // 2. Load root context config: ConfigDevelopment.php or ConfigProduction.php
    if ($appContext->isProduction()) {
        $productionConfigFile = $configFileBaseName . 'Production.php';
        if (is_readable($productionConfigFile)) {
            require_once $productionConfigFile;
        }
    } elseif ($appContext->isDevelopment()) {
        $developmentConfigFile = $configFileBaseName . 'Development.php';
        if (is_readable($developmentConfigFile)) {
            require_once $developmentConfigFile;
        }
    }

    // 3. Load specific context config: E.g. ConfigDevelopmentStaging.php for context Development/Staging
    if (!is_null($appContext->getParent())) {
        $specificContextConfigFile = $configFileBaseName . str_replace('/', '', (string)$appContext) . '.php';
        if (is_readable($specificContextConfigFile)) {
            require_once $specificContextConfigFile;
        }
    }

    // 4. Load override config: ConfigOverride.php
    $overrideConfigFile = $configFileBaseName . 'Override.php';
    if (is_readable($overrideConfigFile)) {
        require_once $overrideConfigFile;
    }
})();

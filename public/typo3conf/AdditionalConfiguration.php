<?php
/**
 * @author Christoph Bessei
 * @version
 */

(function () {
    // We try to follow PHP PDS (http://php-pds.com/) so ROOT_DIR/config/ should contain all configurations.
    $appContext = \TYPO3\CMS\Core\Utility\GeneralUtility::getApplicationContext();
    $configDir = rtrim(dirname(PATH_site), '/') . '/config';
    $configFileBaseName = $configDir . '/Config';

    $config = [];

    // Load config files of current TYPO3 context.

    // 1. Load default config ConfigDefault.php
    $defaultConfigFile = $configFileBaseName . 'Default.php';
    if (is_readable($defaultConfigFile)) {
        $config = array_replace_recursive($config, include $defaultConfigFile);
    }

    // 2. Load root context config: ConfigDevelopment.php or ConfigProduction.php
    if ($appContext->isProduction()) {
        $productionConfigFile = $configFileBaseName . 'Production.php';
        if (is_readable($productionConfigFile)) {
            $config = array_replace_recursive($config, include $productionConfigFile);
        }
    } elseif ($appContext->isDevelopment()) {
        $developmentConfigFile = $configFileBaseName . 'Development.php';
        if (is_readable($developmentConfigFile)) {
            $config = array_replace_recursive($config, include $developmentConfigFile);
        }
    }

    // 3. Load specific context config: E.g. ConfigDevelopmentStaging.php for context Development/Staging
    if (!is_null($appContext->getParent())) {
        $specificContextConfigFile = $configFileBaseName . str_replace('/', '', (string)$appContext) . '.php';
        if (is_readable($specificContextConfigFile)) {
            $config = array_replace_recursive($config, include $specificContextConfigFile);
        }
    }

    // 4. Load override config: ConfigOverride.php
    $overrideConfigFile = $configFileBaseName . 'Override.php';
    if (is_readable($overrideConfigFile)) {
        $config = array_replace_recursive($config, include $overrideConfigFile);
    }

    if (!empty($config)) {
        $GLOBALS['TYPO3_CONF_VARS'] = array_replace_recursive(
            $GLOBALS['TYPO3_CONF_VARS'],
            $config
        );
    }

    // Disable APCU/APC on CLI, it's useless on CLI and causes an exception if apc.enable_cli isn't true.
    if (PHP_SAPI === 'cli') {
        $cacheConfigs = &$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'];
        if (is_array($cacheConfigs)) {
            $disabledBackends = [
                trim(\TYPO3\CMS\Core\Cache\Backend\ApcuBackend::class, '\\'),
                trim(\TYPO3\CMS\Core\Cache\Backend\ApcBackend::class, '\\'),
            ];
            foreach ($cacheConfigs as $key => $cacheConfig) {
                if (in_array(trim($cacheConfig['backend'], '\\'), $disabledBackends)) {
                    $cacheConfigs[$key]['backend'] = \TYPO3\CMS\Core\Cache\Backend\NullBackend::class;
                }
            }
        }
    }
})();

<?php
/**
 * @author Christoph Bessei
 */

/** @var \TYPO3\Surf\Domain\Model\Deployment $deployment */

(function ($deployment) {
    /** @var \TYPO3\Surf\Domain\Model\Deployment $deployment */

    $nodeName = 'Example';
    // SSH with key auth
    $sshHost = '';
    $sshUsername = '';

    // Local composer path
    $composerPath = '/usr/local/bin/composer';

    // Exclude some files from deployment
    $rsyncExcludes = [
        '.idea',
        '.git',
        'Resources/Public/Assets/Src/',
    ];

    // Deployment path, absolute path to project root folder on $node
    $deploymentPath = '/var/www/example/';

    // Git repository of project
    $repositoryUrl = 'git@github.com:example/example.git';

    // TYPO3 webDirectory. See https://wiki.typo3.org/Composer#Composer_Mode
    $webDirectory = 'public';

    // Config for WebOpcacheResetExecuteTask
    // $baseUrl is only needed if you set $enableOpcacheClearTask to true
    $enableOpcacheClearTask = false;
    // Absolute url to TYPO3 frontend
    $baseUrl = 'http://www.example.com/example-folder/';

    /**********************************************/
    /* That's all, stop editing! Happy deploying. */
    /**********************************************/

    // Configure node
    $node = new \TYPO3\Surf\Domain\Model\Node($nodeName);
    $node->setHostname($sshHost);
    $node->setOption('username', $sshUsername);
    $node->setOption('composerCommandPath', $composerPath);

    // Configure application
    $application = new \TYPO3\Surf\Application\TYPO3\CMS();
    $application->setDeploymentPath($deploymentPath);
    $application->setOption('repositoryUrl', $repositoryUrl);
    $application->setOption('webDirectory', $webDirectory);
    $application->setOption('directories', []);
    $application->setOption('rsyncExcludes', $rsyncExcludes);
    if ($enableOpcacheClearTask) {
        $application->setOption('baseUrl', $baseUrl);
        $application->setOption(
            'scriptBasePath',
            \TYPO3\Flow\Utility\Files::concatenatePaths([$deployment->getWorkspacePath($application), $webDirectory])
        );
    }
    $application->addNode($node);

    $deployment->addApplication($application);
    $deployment->onInitialize(
        function () use ($deployment, $application) {
            /** @var \TYPO3\Surf\Domain\Model\SimpleWorkflow $workflow */
            $workflow = $deployment->getWorkflow();

            // Add clear opcache task
            if (!empty($application->getOption('baseUrl')) && !empty($application->getOption('scriptBasePath'))) {
                $workflow->addTask(
                    \TYPO3\Surf\Task\Php\WebOpcacheResetCreateScriptTask::class,
                    'package',
                    $application
                );
                $workflow->addTask(
                    \TYPO3\Surf\Task\Php\WebOpcacheResetExecuteTask::class,
                    'switch',
                    $application
                );
            }

            // Add configuration task (Copy config from /config to sharedFolder/Configuration)
            $workflow->afterTask(
                \TYPO3\Surf\Task\TYPO3\CMS\SymlinkDataTask::class,
                [\BIT\TYPO3\Install\Task\RsyncConfigurationTask::class],
                $application
            );
        }
    );

})(
    $deployment
);

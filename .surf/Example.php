<?php
/**
 * @author Christoph Bessei
 */

/** @var \TYPO3\Surf\Domain\Model\Deployment $deployment */

(function ($deployment) {

    $nodeName = 'Example';
    // SSH with key auth
    $sshHost = '';
    $sshUsername = '';

    // Composer path on $node
    $composerPathOnNode = '/usr/local/bin/composer';


    $node = new \TYPO3\Surf\Domain\Model\Node($nodeName);
    $node->setHostname($sshHost);
    $node->setOption('username', $sshUsername);
    $node->setOption('composerCommandPath', $composerPathOnNode);


    // Deployment path, path to root folder on $node
    $deploymentPath = '';
    $repositoryUrl = '';

    $application = new \TYPO3\Surf\Application\TYPO3\CMS();
    $application->setDeploymentPath($deploymentPath);
    $application->setOption('repositoryUrl', $repositoryUrl);
    $application->setOption('webDirectory', 'public');
    $application->setOption('directories', []);
    $application->addNode($node);

    /** @var \TYPO3\Surf\Domain\Model\Deployment $deployment */
    $deployment->addApplication($application);

})($deployment);

<?php

/*
 * This file is part of the "be_static_auth" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

if (\TYPO3\CMS\Core\Core\Environment::getContext()->isDevelopment()) {
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['backend']['loginProviders']['static_auth'] = [
        'provider' => \Codemonkey1988\BeStaticAuth\LoginProvider\StaticAdminProvider::class,
        'sorting' => 30,
        'iconIdentifier' => 'actions-globe',
        'label' => 'LLL:EXT:be_static_auth/Resources/Private/Language/locallang_be.xlf:backendLogin.switch.label'
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
        'be_static_auth',
        'auth',
        'tx_staticlogin_service',
        [
            'title' => 'Static Login Authentication',
            'description' => 'Static login service for backend',
            'subtype' => 'getUserBE,authUserBE',
            'available' => true,
            'priority' => 75,
            'quality' => 50,
            'os' => '',
            'exec' => '',
            'className' => \Codemonkey1988\BeStaticAuth\Service\StaticAuthenticationService::class,
        ]
    );
}

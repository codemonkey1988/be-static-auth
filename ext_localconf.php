<?php
defined('TYPO3_MODE') || die();

if (\TYPO3\CMS\Core\Utility\GeneralUtility::getApplicationContext()->isDevelopment()) {
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['backend']['loginProviders']['static_auth'] = [
        'provider' => \Codemonkey1988\BeStaticAuth\LoginProvider\StaticAdminProvider::class,
        'sorting' => 30,
        'icon-class' => 'fa-key',
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
            'className' => \Codemonkey1988\BeStaticAuth\Service\StaticAuthenticationService::class
        ]
    );
}

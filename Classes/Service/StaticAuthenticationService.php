<?php

declare(strict_types=1);

/*
 * This file is part of the "be_static_auth" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\BeStaticAuth\Service;

use Codemonkey1988\BeStaticAuth\Domain\Model\Dto\ExtensionConfiguration;
use Codemonkey1988\BeStaticAuth\UserProvider\BackendUserProvider;
use TYPO3\CMS\Core\Authentication\AbstractUserAuthentication;
use TYPO3\CMS\Core\Service\AbstractService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class StaticAuthenticationService
 */
class StaticAuthenticationService extends AbstractService
{
    /**
     * @var array
     */
    protected $loginData;

    /**
     * @var array
     */
    protected $authenticationInformation = [];

    /**
     * @var ExtensionConfiguration
     */
    protected $extensionConfiguration;

    /**
     * Initializes authentication for this service.
     *
     * @param string $subType : Subtype for authentication (either "getUserFE" or "getUserBE")
     * @param array $loginData : Login data submitted by user and preprocessed by AbstractUserAuthentication
     * @param array $authenticationInformation : Additional TYPO3 information for authentication services (unused here)
     * @param AbstractUserAuthentication $parentObject Calling object
     */
    public function initAuth($subType, array $loginData, array $authenticationInformation, AbstractUserAuthentication $parentObject)
    {
        // Store login and authentication data
        $this->loginData = $loginData;
        $this->authenticationInformation = $authenticationInformation;
        $this->extensionConfiguration = GeneralUtility::makeInstance(ConfigurationService::class)
            ->getConfiguration();
    }

    /**
     * This function returns the user record back to the AbstractUserAuthentication.
     * It does not mean that user is authenticated, it means only that user is found. This
     * function makes sure that user cannot be authenticated by any other service
     * if user tries to use OpenID to authenticate.
     *
     * @return mixed User record (content of fe_users/be_users as appropriate for the current mode)
     */
    public function getUser()
    {
        if ($this->loginData['status'] !== 'login' || $this->authenticationInformation['loginType'] !== 'BE') {
            return false;
        }

        $username = $this->getConfiguredUsername();
        $userProvider = GeneralUtility::makeInstance(BackendUserProvider::class, $this->authenticationInformation);
        $userRecord = $userProvider->getUserByUsername($username);

        if (empty($userRecord)) {
            $userRecordWithoutRestrictions = $userProvider->getUserByUsername($username, false);

            if (empty($userRecordWithoutRestrictions)) {
                $userProvider->createAdminUser($username);
                $userRecord = $this->getUser();
            } else {
                $userProvider->restoreUser($userRecordWithoutRestrictions);
                $userRecord = $this->getUser();
            }
        }

        return $userRecord;
    }

    /**
     * Authenticates user
     *
     * @param array $userRecord User record
     * @return int Code that shows if user is really authenticated.
     */
    public function authUser(array $userRecord): int
    {
        $result = 100;

        if ($this->getConfiguredUsername() === $userRecord['username']) {
            $result = 200;
        }

        return $result;
    }

    /**
     * @return string
     */
    protected function getConfiguredUsername(): string
    {
        return  $this->extensionConfiguration->getUsername();
    }
}

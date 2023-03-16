<?php

declare(strict_types=1);

/*
 * This file is part of the "be_static_auth" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\BeStaticAuth\Service;

use Codemonkey1988\BeStaticAuth\UserProvider\BackendUserProvider;
use TYPO3\CMS\Core\Authentication\AbstractUserAuthentication;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Exception;
use TYPO3\CMS\Core\Service\AbstractService;

/**
 * @phpstan-type AuthenticationInformation array<string, mixed>
 */
class StaticAuthenticationService extends AbstractService
{
    const DEFAULT_USERNAME = 'administrator';

    /**
     * @var array{status: string|null, uname: string|null, ident: string|null}
     */
    protected array $loginData;

    protected ExtensionConfiguration $extensionConfiguration;

    protected BackendUserProvider $backendUserProvider;

    /**
     * @required
     */
    public function setExtensionConfiguration(ExtensionConfiguration $extensionConfiguration): void
    {
        $this->extensionConfiguration = $extensionConfiguration;
    }

    /**
     * @required
     */
    public function setBackendUserProvider(BackendUserProvider $backendUserProvider): void
    {
        $this->backendUserProvider = $backendUserProvider;
    }

    /**
     * @param string $subType Subtype for authentication (either "getUserFE" or "getUserBE")
     * @param array{status: string|null, uname: string|null, ident: string|null} $loginData Login data submitted by user and preprocessed by AbstractUserAuthentication
     * @param array<string, mixed> $authenticationInformation Additional TYPO3 information for authentication services (unused here)
     * @param AbstractUserAuthentication $parentObject Calling object
     */
    public function initAuth(
        string $subType,
        array $loginData,
        array $authenticationInformation,
        AbstractUserAuthentication $parentObject
    ): void {
        $this->loginData = $loginData;
        $this->backendUserProvider->setAuthenticationInformation($authenticationInformation);
    }

    /**
     * This function returns the user record back to the AbstractUserAuthentication.
     * It does not mean that user is authenticated, it means only that user is found. This
     * function makes sure that user cannot be authenticated by any other service
     * if user tries to use OpenID to authenticate.
     *
     * @return array<string, mixed>|null User record (content of fe_users/be_users as appropriate for the current mode)
     */
    public function getUser(): ?array
    {
        if ($this->loginData['status'] !== 'login'
            || $this->backendUserProvider->getAuthenticationInformation()['loginType'] !== 'BE') {
            return null;
        }

        $username = $this->getConfiguredUsername();
        $userRecord = $this->backendUserProvider->getUserByUsername($username);

        if ($userRecord === []) {
            $userRecordWithoutRestrictions = $this->backendUserProvider->getUserByUsername($username, false);
            try {
                if ($userRecordWithoutRestrictions === []) {
                    $this->backendUserProvider->createAdminUser($username);
                } else {
                    $this->backendUserProvider->restoreUser($userRecordWithoutRestrictions);
                }
            } catch (Exception $e) {
                return null;
            }
            $userRecord = $this->getUser();
        }

        return is_array($userRecord) ? $userRecord : null;
    }

    /**
     * @param array{username?: string} $userRecord
     */
    public function authUser(array $userRecord): int
    {
        $result = 100;

        if (isset($userRecord['username']) && $this->getConfiguredUsername() === $userRecord['username']) {
            $result = 200;
        }

        return $result;
    }

    protected function getConfiguredUsername(): string
    {
        try {
            $username = $this->extensionConfiguration->get('be_static_auth', 'username');
        } catch (ExtensionConfigurationExtensionNotConfiguredException | ExtensionConfigurationPathDoesNotExistException $e) {
            $username = self::DEFAULT_USERNAME;
        }
        return $username ?: self::DEFAULT_USERNAME;
    }
}

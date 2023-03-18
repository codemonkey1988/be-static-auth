<?php

declare(strict_types=1);

/*
 * This file is part of the "be_static_auth" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\BeStaticAuth\Service;

use Codemonkey1988\BeStaticAuth\Configuration;
use Codemonkey1988\BeStaticAuth\UserProvider\BackendUserFactory;
use Codemonkey1988\BeStaticAuth\UserProvider\BackendUserProvider;
use Codemonkey1988\BeStaticAuth\UserProvider\UserNotFoundException;
use TYPO3\CMS\Core\Authentication\AbstractAuthenticationService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @phpstan-type AuthenticationInformation array{
 *     loginType: string,
 *     db_user: array{
 *         table: string
 *     }
 * }
 */
final class StaticAuthenticationService extends AbstractAuthenticationService
{
    private Configuration $configuration;

    private BackendUserProvider $backendUserProvider;

    public function __construct(Configuration $configuration, BackendUserProvider $backendUserProvider)
    {
        $this->configuration = $configuration;
        $this->backendUserProvider = $backendUserProvider;
    }

    /**
     * @param array{status: string} $loginData
     * @param AuthenticationInformation $authInfo
     */
    public function initAuth($mode, $loginData, $authInfo, $pObj): void
    {
        parent::initAuth($mode, $loginData, $authInfo, $pObj);
        if (!empty($authInfo['db_user']['table'])) {
            $this->backendUserProvider->setTableName($authInfo['db_user']['table']);
        }
    }

    /**
     * @return array{username: string}|null
     */
    public function getUser(): ?array
    {
        if ($this->login['status'] !== 'login' || $this->authInfo['loginType'] !== 'BE') {
            return null;
        }

        $username = $this->configuration->getUsername();
        $enableClause = $this->authInfo['db_user']['enable_clause'] ?? null;
        try {
            // Try to get an active user.
            $userRecord = $this->backendUserProvider->getUserByUsernameWithoutRestrictions($username, $enableClause);
        } catch (UserNotFoundException $e) {
            try {
                // Check if there is a disabled or deleted user with the given username.
                $this->backendUserProvider->getUserByUsernameWithoutRestrictions($username);
                if ($this->logger !== null) {
                    $this->logger->warning(sprintf(
                        'Tried to login with username "%s" using static authentication provider. User is disabled or deleted.',
                        $username
                    ));
                }
                return null;
            } catch (UserNotFoundException $e) {
                try {
                    // Try to create a new user record, if no user with given username exists.
                    $userRecord = GeneralUtility::makeInstance(BackendUserFactory::class)->createAdminUserWithRandomPassword($username);
                } catch (UserNotFoundException $e) {
                    return null;
                }
            } catch (\Throwable $e) {
                return null;
            }
        }

        return $userRecord;
    }

    /**
     * @param array{username?: string} $userRecord
     */
    public function authUser(array $userRecord): int
    {
        $result = 100;

        if (isset($userRecord['username']) && $this->configuration->getUsername() === $userRecord['username']) {
            $result = 200;
        }

        return $result;
    }
}

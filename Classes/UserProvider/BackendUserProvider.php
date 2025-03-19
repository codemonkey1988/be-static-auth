<?php

declare(strict_types=1);

/*
 * This file is part of the "be_static_auth" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\BeStaticAuth\UserProvider;

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Expression\CompositeExpression;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class BackendUserProvider implements UserProviderInterface
{
    private Context $context;

    private ConnectionPool $connectionPool;

    private string $tableName = 'be_users';

    public function __construct(Context $context, ConnectionPool $connectionPool)
    {
        $this->context = $context;
        $this->connectionPool = $connectionPool;
    }

    public function setTableName(string $tableName): void
    {
        $this->tableName = $tableName;
    }

    /**
     * @throws UserAlreadyExistException
     */
    public function createAdminUser(string $username, string $hashedPassword): void
    {
        if ($this->userExists($username)) {
            throw new UserAlreadyExistException(
                sprintf('A user with username "%s" already exists', $username),
                1679131549
            );
        }

        $currentTimestamp = $this->context->getPropertyFromAspect('date', 'timestamp');
        $data = [
            'username' => $username,
            'password' => $hashedPassword,
            'tstamp' => $currentTimestamp,
            'crdate' => $currentTimestamp,
            'description' => 'Auto generated by be_static_auth plugin',
            'admin' => 1,
        ];
        $this->connectionPool->getConnectionForTable($this->tableName)->insert($this->tableName, $data);
    }

    /**
     * @return array{uid: int, pid: int, username: string}
     * @throws UserNotFoundException
     */
    public function getUserByUsernameWithoutRestrictions(string $username, ?CompositeExpression $additionalConditions = null): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->tableName);
        $queryBuilder->getRestrictions()->removeAll();

        $conditions = [
            $queryBuilder->expr()->eq(
                'username',
                $queryBuilder->createNamedParameter($username)
            ),
        ];

        if (!empty($additionalConditions)) {
            $conditions[] = $additionalConditions;
        }

        $result = $queryBuilder
            ->select('*')
            ->from($this->tableName)
            ->where(...$conditions)
            ->executeQuery();

        /** @var array{uid: int, pid: int, username: string}|false $user */
        $user = $result->fetchAssociative();
        if ($user === false) {
            throw new UserNotFoundException(
                sprintf('No user found for username "%s".', $username),
                1679126889
            );
        }
        if ($result->rowCount() > 1) {
            throw new \UnexpectedValueException(
                sprintf('Too many records found for username "%s".', $username),
                1549995857
            );
        }

        return $user;
    }

    private function userExists(string $username): bool
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->tableName);
        $queryBuilder->getRestrictions()->removeAll();

        $conditions = [
            $queryBuilder->expr()->eq(
                'username',
                $queryBuilder->createNamedParameter($username)
            ),
        ];

        $result = $queryBuilder
            ->select('*')
            ->from($this->tableName)
            ->where(...$conditions)
            ->executeQuery();

        return $result->rowCount() > 0;
    }
}

<?php

/*
 * This file is part of the "be_static_auth" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\BeStaticAuth\UserProvider;

/**
 * Interface UserProviderInterface
 */
interface UserProviderInterface
{
    /**
     * Get a user by its username.
     *
     * @param string $username
     * @param bool $respectEnableFields
     * @return array
     */
    public function getUserByUsername(string $username, $respectEnableFields = true): array;

    /**
     * Creates a new admin user with.
     *
     * @param string $username
     */
    public function createAdminUser(string $username);

    /**
     * Sets the deleted flag to 0 for the given user record uid.
     *
     * @param array $userRecord
     */
    public function restoreUser(array $userRecord);
}

<?php

/*
 * This file is part of the "be_static_auth" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\BeStaticAuth\UserProvider;

interface UserProviderInterface
{
    /**
     * Get a user by its username.
     */
    public function getUserByUsername(string $username, $respectEnableFields = true): array;

    /**
     * Creates a new admin user with.
     */
    public function createAdminUser(string $username);

    /**
     * Sets the deleted flag to 0 for the given user record uid.
     */
    public function restoreUser(array $userRecord);
}

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
     *
     * @return array<string, mixed>
     */
    public function getUserByUsername(string $username, bool $respectEnableFields = true): array;

    /**
     * Creates a new admin user with.
     */
    public function createAdminUser(string $username): void;

    /**
     * Sets the deleted flag to 0 for the given user record uid.
     *
     * @param array<string, mixed> $userRecord
     */
    public function restoreUser(array $userRecord): void;
}

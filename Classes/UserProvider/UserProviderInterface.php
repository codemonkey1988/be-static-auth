<?php

/*
 * This file is part of the "be_static_auth" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\BeStaticAuth\UserProvider;

use TYPO3\CMS\Core\Database\Query\Expression\CompositeExpression;

interface UserProviderInterface
{
    /**
     * Get a user by its username.
     *
     * @return array<string, mixed>
     */
    public function getUserByUsernameWithoutRestrictions(string $username, CompositeExpression $additionalConditions = null): array;

    /**
     * Creates a new admin user with admin privileges.
     */
    public function createAdminUser(string $username, string $hashedPassword): void;
}

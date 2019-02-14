<?php
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
     * @return void
     */
    public function createAdminUser(string $username);

    /**
     * Sets the deleted flag to 0 for the given user record uid.
     *
     * @param array $userRecord
     * @return void
     */
    public function restoreUser(array $userRecord);
}

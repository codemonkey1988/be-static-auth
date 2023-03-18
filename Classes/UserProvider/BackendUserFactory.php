<?php

declare(strict_types=1);

/*
 * This file is part of the "be_static_auth" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\BeStaticAuth\UserProvider;

use Codemonkey1988\BeStaticAuth\Password\RandomPasswordGenerator;
use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashInterface;

final class BackendUserFactory
{
    private BackendUserProvider $backendUserProvider;

    private PasswordHashInterface $passwordHashStrategy;

    private RandomPasswordGenerator $randomPasswordGenerator;

    public function __construct(
        BackendUserProvider $backendUserProvider,
        PasswordHashInterface $passwordHashStrategy,
        RandomPasswordGenerator $randomPasswordGenerator
    ) {
        $this->backendUserProvider = $backendUserProvider;
        $this->passwordHashStrategy = $passwordHashStrategy;
        $this->randomPasswordGenerator = $randomPasswordGenerator;
    }

    /**
     * @return array{username: string}
     * @throws UserNotFoundException
     * @throws UserAlreadyExistException
     */
    public function createAdminUserWithRandomPassword(string $username): array
    {
        $plainPassword = $this->randomPasswordGenerator->generate(60);
        /** @var string|null $hashedPassword In TYPO3 v12, the return value is nullable*/
        $hashedPassword = $this->passwordHashStrategy->getHashedPassword($plainPassword);
        if ($hashedPassword === null) {
            throw new \RuntimeException('Cannot hash password', 1679133298);
        }
        $this->backendUserProvider->createAdminUser($username, $hashedPassword);

        return $this->backendUserProvider->getUserByUsernameWithoutRestrictions($username);
    }
}

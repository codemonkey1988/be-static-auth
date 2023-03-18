<?php

declare(strict_types=1);

/*
 * This file is part of the "be_static_auth" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\BeStaticAuth;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;

final class Configuration
{
    private const DEFAULT_USERNAME = 'administrator';

    private string $username;

    public function __construct(ExtensionConfiguration $extensionConfiguration)
    {
        try {
            $username = (string)$extensionConfiguration->get('be_static_auth', 'username');
        } catch (\Throwable $e) {
            $username = self::DEFAULT_USERNAME;
        }
        $this->username = $username ?: self::DEFAULT_USERNAME;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}

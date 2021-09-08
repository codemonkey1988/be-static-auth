<?php

declare(strict_types=1);

/*
 * This file is part of the "be_static_auth" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\BeStaticAuth\Domain\Model\Dto;

/**
 * Class ExtensionConfiguration
 */
class ExtensionConfiguration
{
    const DEFAULT_USERNAME = 'administrator';

    /**
     * @var string
     */
    protected $username;

    public function __construct(array $configuration)
    {
        $this->username = (string)$configuration['username'] ?? self::DEFAULT_USERNAME;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }
}

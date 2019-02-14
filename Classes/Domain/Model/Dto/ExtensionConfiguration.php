<?php
declare(strict_types=1);
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
        $this->username = (string)$configuration['username'] ?: self::DEFAULT_USERNAME;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }
}

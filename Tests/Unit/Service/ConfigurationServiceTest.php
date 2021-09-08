<?php

declare(strict_types=1);

/*
 * This file is part of the "be_static_auth" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\BeStaticAuth\Tests\Unit\Service;

use Codemonkey1988\BeStaticAuth\Domain\Model\Dto\ExtensionConfiguration;
use Codemonkey1988\BeStaticAuth\Service\ConfigurationService;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class ConfigurationServiceTest extends UnitTestCase
{
    /**
     * @test
     */
    public function initializeWithMissingConfiguration()
    {
        $subject = new ConfigurationService();

        self::assertInstanceOf(ExtensionConfiguration::class, $subject->getConfiguration());
    }

    /**
     * @test
     */
    public function initializeWithValidConfiguration()
    {
        $testConfiguration = [
            'username' => 'admin',
        ];
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['be_static_auth'] = $testConfiguration;

        $subject = new ConfigurationService();
        $configuration = $subject->getConfiguration();

        self::assertSame('admin', $configuration->getUsername());
    }
}

<?php

declare(strict_types=1);

/*
 * This file is part of the "be_static_auth" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\BeStaticAuth\Tests\Functional;

use Codemonkey1988\BeStaticAuth\Configuration;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class ConfigurationTest extends FunctionalTestCase
{
    protected function setUp(): void
    {
        $this->testExtensionsToLoad = [
            'typo3conf/ext/be_static_auth'
        ];

        parent::setUp();
    }

    /**
     * @test
     */
    public function gettingUsernameWithoutConfigurationWillReturnDefaultUsername(): void
    {
        /** @var Configuration $subject */
        $subject = $this->get(Configuration::class);

        self::assertSame('administrator', $subject->getUsername());
    }

    /**
     * @test
     */
    public function gettingUsernameWithConfigurationWillReturnConfiguredUsername(): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['be_static_auth']['username'] = 'testuser';

        /** @var Configuration $subject */
        $subject = $this->get(Configuration::class);

        self::assertSame('testuser', $subject->getUsername());
    }
}

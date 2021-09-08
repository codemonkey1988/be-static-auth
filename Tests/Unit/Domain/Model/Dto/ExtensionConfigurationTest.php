<?php

declare(strict_types=1);

/*
 * This file is part of the "be_static_auth" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\BeStaticAuth\Tests\Unit\Domain\Model\Dto;

use Codemonkey1988\BeStaticAuth\Domain\Model\Dto\ExtensionConfiguration;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class ExtensionConfigurationTest extends UnitTestCase
{
    /**
     * @test
     */
    public function initializeWithEmptyConfiguration()
    {
        $subject = new ExtensionConfiguration([]);

        self::assertSame('administrator', $subject->getUsername());
    }

    /**
     * @test
     */
    public function initializeWithClientIdConfiguration()
    {
        $subject = new ExtensionConfiguration([
            'username' => 'admin',
        ]);

        self::assertSame('admin', $subject->getUsername());
    }
}

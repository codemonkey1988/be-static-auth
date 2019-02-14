<?php
declare(strict_types=1);
namespace Codemonkey1988\BeStaticAuth\Tests\Unit\Domain\Model\Dto;

use Codemonkey1988\BeStaticAuth\Domain\Model\Dto\ExtensionConfiguration;
use Nimut\TestingFramework\TestCase\UnitTestCase;

class ExtensionConfigurationTest extends UnitTestCase
{
    /**
     * @test
     */
    public function initializeWithEmptyConfiguration()
    {
        $subject = new ExtensionConfiguration([]);

        $this->assertSame('administrator', $subject->getUsername());
    }

    /**
     * @test
     */
    public function initializeWithClientIdConfiguration()
    {
        $subject = new ExtensionConfiguration([
            'username' => 'admin',
        ]);

        $this->assertSame('admin', $subject->getUsername());
    }
}

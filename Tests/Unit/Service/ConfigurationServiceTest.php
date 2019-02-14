<?php
declare(strict_types=1);
namespace Codemonkey1988\BeStaticAuth\Tests\Unit\Service;

use Codemonkey1988\BeStaticAuth\Domain\Model\Dto\ExtensionConfiguration;
use Codemonkey1988\BeStaticAuth\Service\ConfigurationService;
use Nimut\TestingFramework\TestCase\UnitTestCase;

class ConfigurationServiceTest extends UnitTestCase
{
    /**
     * @test
     */
    public function initializeWithMissingConfiguration()
    {
        $subject = new ConfigurationService();

        $this->assertInstanceOf(ExtensionConfiguration::class, $subject->getConfiguration());
    }

    /**
     * @test
     */
    public function initializeWithValidConfiguration()
    {
        $testConfiguration = [
            'username' => 'admin',
        ];
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['be_static_auth'] = serialize($testConfiguration);
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['be_static_auth'] = $testConfiguration;

        $subject = new ConfigurationService();
        $configuration = $subject->getConfiguration();

        $this->assertSame('admin', $configuration->getUsername());
    }
}

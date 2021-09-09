<?php

declare(strict_types=1);

namespace Codemonkey1988\BeStaticAuth\Tests\Unit\Service;

use Codemonkey1988\BeStaticAuth\Service\StaticAuthenticationService;
use Codemonkey1988\BeStaticAuth\UserProvider\BackendUserProvider;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class StaticAuthenticationServiceTest extends UnitTestCase
{
    /**
     * @test
     */
    public function loginUserDoExistAndWillReturned()
    {
        $backendUserMock = $this->getBackendUserMock('BE');
        $backendUserMock->expects(self::once())->method('getUserByUsername')->willReturn(['username' => 'admin']);
        $backendUserMock->expects(self::never())->method('createAdminUser');
        $backendUserMock->expects(self::never())->method('restoreUser');
        $extensionConfigurationMock = $this->getExtensionConfigurationMock('admin');

        $staticAuthenticationService = new StaticAuthenticationService();
        $staticAuthenticationService->setBackendUserProvider($backendUserMock);
        $staticAuthenticationService->setExtensionConfiguration($extensionConfigurationMock);
        $staticAuthenticationService->initAuth('', ['status' => 'login'], [], $this->getAbstractUserAuthentication());
        $user = $staticAuthenticationService->getUser();

        self::assertSame('admin', $user['username']);
    }

    /**
     * @test
     */
    public function loginUserDoNotExistAndWillBeCreated()
    {
        $backendUserMock = $this->getBackendUserMock('BE');
        $backendUserMock->expects(self::exactly(3))->method('getUserByUsername')->will(self::onConsecutiveCalls(
            [],
            [],
            ['username' => 'admin']
        ));
        $backendUserMock->expects(self::once())->method('createAdminUser');
        $backendUserMock->expects(self::never())->method('restoreUser');
        $extensionConfigurationMock = $this->getExtensionConfigurationMock('admin');

        $staticAuthenticationService = new StaticAuthenticationService();
        $staticAuthenticationService->setBackendUserProvider($backendUserMock);
        $staticAuthenticationService->setExtensionConfiguration($extensionConfigurationMock);
        $staticAuthenticationService->initAuth('', ['status' => 'login'], [], $this->getAbstractUserAuthentication());
        $user = $staticAuthenticationService->getUser();

        self::assertSame('admin', $user['username']);
    }

    /**
     * @test
     */
    public function loginUserDoExistDisabledAndWillBeRestored()
    {
        $backendUserMock = $this->getBackendUserMock('BE');
        $backendUserMock->expects(self::exactly(3))->method('getUserByUsername')->will(self::onConsecutiveCalls(
            [],
            ['username' => 'admin'],
            ['username' => 'admin']
        ));
        $backendUserMock->expects(self::never())->method('createAdminUser');
        $backendUserMock->expects(self::once())->method('restoreUser');
        $extensionConfigurationMock = $this->getExtensionConfigurationMock('admin');

        $staticAuthenticationService = new StaticAuthenticationService();
        $staticAuthenticationService->setBackendUserProvider($backendUserMock);
        $staticAuthenticationService->setExtensionConfiguration($extensionConfigurationMock);
        $staticAuthenticationService->initAuth('', ['status' => 'login'], [], $this->getAbstractUserAuthentication());
        $user = $staticAuthenticationService->getUser();

        self::assertSame('admin', $user['username']);
    }

    protected function getBackendUserMock(string $loginType): MockObject
    {
        $backendUserMock = $this->getMockBuilder(BackendUserProvider::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'setAuthenticationInformation',
                'getAuthenticationInformation',
                'getUserByUsername',
                'createAdminUser',
                'restoreUser',
            ])
            ->getMock();
        $backendUserMock->method('getAuthenticationInformation')
            ->willReturn(['loginType' => $loginType]);
        return $backendUserMock;
    }

    protected function getExtensionConfigurationMock(string $username): MockObject
    {
        $extensionConfigurationMock = $this->getMockBuilder(ExtensionConfiguration::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get'])
            ->getMock();
        $extensionConfigurationMock->method('get')->willReturn($username);
        return $extensionConfigurationMock;
    }

    protected function getAbstractUserAuthentication(): MockObject
    {
        $userAuthentication = $this->getMockBuilder(BackendUserAuthentication::class)
            ->disableOriginalConstructor()
            ->getMock();
        return $userAuthentication;
    }
}

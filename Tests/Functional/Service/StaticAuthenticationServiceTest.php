<?php

declare(strict_types=1);

/*
 * This file is part of the "be_static_auth" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\BeStaticAuth\Tests\Functional\Service;

use Codemonkey1988\BeStaticAuth\Service\StaticAuthenticationService;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * @covers \Codemonkey1988\BeStaticAuth\Service\StaticAuthenticationService
 */
final class StaticAuthenticationServiceTest extends FunctionalTestCase
{
    private StaticAuthenticationService $subject;

    protected function setUp(): void
    {
        $this->testExtensionsToLoad = [
            'typo3conf/ext/be_static_auth'
        ];

        parent::setUp();

        $backendUserAuthentication = $this->initBackendUserAuthentication();
        $this->subject = $this->get(StaticAuthenticationService::class);
        $this->subject->initAuth(
            '',
            [
                'status' => 'login',
                'uname' => null,
                'ident' => null,
            ],
            $backendUserAuthentication->getAuthInfoArray(),
            $backendUserAuthentication
        );
    }

    /**
     * @test
     */
    public function loginWithNonExistingUserWillCreateAdminUserWithDefaultUsername(): void
    {
        self::assertCount(0, $this->getAllRecords('be_users'));

        $user = $this->subject->getUser();

        self::assertIsArray($user);
        self::assertSame('administrator', $user['username']);
        self::assertCount(1, $this->getAllRecords('be_users'));
    }

    /**
     * @test
     */
    public function loginWithNonExistingUserAndGivenUsernameWillCreateAdminUserWithGivenUsername(): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['be_static_auth']['username'] = 'testuser';

        self::assertCount(0, $this->getAllRecords('be_users'));

        $user = $this->subject->getUser();

        self::assertIsArray($user);
        self::assertSame('testuser', $user['username']);
        self::assertCount(1, $this->getAllRecords('be_users'));
    }

    /**
     * @test
     */
    public function loginWithExistingAdminUserWillNotCreateNewUser(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/BeUsers.csv');

        self::assertCount(1, $this->getAllRecords('be_users'));

        $user = $this->subject->getUser();

        self::assertIsArray($user);
        self::assertSame('administrator', $user['username']);
        self::assertCount(1, $this->getAllRecords('be_users'));
    }

    /**
     * @test
     */
    public function loginWithExistingButDisabledAdminUserWillEnableUser(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/DisabledBeUsers.csv');

        self::assertCount(1, $this->getAllRecords('be_users'));

        $user = $this->subject->getUser();

        self::assertIsArray($user);
        self::assertSame('administrator', $user['username']);
        self::assertSame(0, $user['disable']);
        self::assertCount(1, $this->getAllRecords('be_users'));
    }

    private function initBackendUserAuthentication(): BackendUserAuthentication
    {
        $GLOBALS['TYPO3_REQUEST'] = new ServerRequest('http://localhost', 'GET');
        $backendUserAuthentication = GeneralUtility::makeInstance(BackendUserAuthentication::class);
        $backendUserAuthentication->start();

        return $backendUserAuthentication;
    }
}

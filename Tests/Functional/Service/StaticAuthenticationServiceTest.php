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
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * @covers \Codemonkey1988\BeStaticAuth\Service\StaticAuthenticationService
 * @phpstan-import-type AuthenticationInformation from StaticAuthenticationService
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

        $typo3Version = new Typo3Version();
        $backendUserAuthentication = $this->initBackendUserAuthentication();
        if (version_compare($typo3Version->getBranch(), '12.3', '>=')) {
            $request = new ServerRequest();
            /** @var AuthenticationInformation $authInfo */
            $authInfo = $backendUserAuthentication->getAuthInfoArray($request);
        } else {
            /** @var AuthenticationInformation $authInfo */
            $authInfo = $backendUserAuthentication->getAuthInfoArray();
        }
        $this->subject = $this->get(StaticAuthenticationService::class);
        $this->subject->initAuth(
            'auth',
            [
                'status' => 'login',
                'uname' => null,
                'ident' => null,
            ],
            $authInfo,
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
    public function loginWithExistingButDisabledAdminUserFail(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/DisabledBeUsers.csv');

        self::assertCount(1, $this->getAllRecords('be_users'));

        $user = $this->subject->getUser();

        self::assertNull($user);
    }

    /**
     * @test
     */
    public function authenticateUserWithConfiguredUsernameWillReturn200(): void
    {
        $user = [
            'username' => 'administrator',
        ];

        self::assertSame(200, $this->subject->authUser($user));
    }

    /**
     * @test
     */
    public function authenticateUserWithMissingUsernameWillReturn100(): void
    {
        $user = [];

        self::assertSame(100, $this->subject->authUser($user));
    }

    /**
     * @test
     */
    public function authenticateUserWithNonConfiguredUsernameWillReturn100(): void
    {
        $user = [
            'username' => 'user-does-not-exist',
        ];

        self::assertSame(100, $this->subject->authUser($user));
    }

    private function initBackendUserAuthentication(): BackendUserAuthentication
    {
        $request = new ServerRequest('http://localhost', 'GET');
        $backendUserAuthentication = GeneralUtility::makeInstance(BackendUserAuthentication::class);
        $backendUserAuthentication->start($request);

        return $backendUserAuthentication;
    }
}

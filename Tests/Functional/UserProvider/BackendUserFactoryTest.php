<?php

declare(strict_types=1);

/*
 * This file is part of the "be_static_auth" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\BeStaticAuth\Tests\Functional\UserProvider;

use Codemonkey1988\BeStaticAuth\UserProvider\BackendUserFactory;
use Codemonkey1988\BeStaticAuth\UserProvider\UserAlreadyExistException;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * @covers \Codemonkey1988\BeStaticAuth\UserProvider\BackendUserFactory
 */
class BackendUserFactoryTest extends FunctionalTestCase
{
    private BackendUserFactory $subject;

    protected function setUp(): void
    {
        $this->testExtensionsToLoad = [
            'typo3conf/ext/be_static_auth'
        ];

        parent::setUp();

        $this->subject = $this->get(BackendUserFactory::class);
    }

    /**
     * @test
     */
    public function creatingAdminUserWillAddDatabaseRecord(): void
    {
        self::assertCount(0, $this->getAllRecords('be_users'));

        $this->subject->createAdminUserWithRandomPassword('admin');

        $records = $this->getAllRecords('be_users');
        self::assertCount(1, $records);
        self::assertSame('admin', $records[0]['username']);
        self::assertSame(1, $records[0]['admin']);
        self::assertNotEmpty($records[0]['password']);
    }

    /**
     * @return list<array{fileName: string}>
     */
    public static function adminUserDataProvider(): array
    {
        return [
            [
                'fileName' => 'BeUsers.csv',
            ],
            [
                'fileName' => 'DisabledBeUsers.csv',
            ],
            [
                'fileName' => 'DeletedBeUsers.csv',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider adminUserDataProvider
     */
    public function creatingAdminUserWithExistingActiveUserWillThrowException(string $fileName): void
    {
        $this->importCSVDataSet(sprintf('%s/../Fixtures/%s', __DIR__, $fileName));

        $this->expectException(UserAlreadyExistException::class);
        $this->expectExceptionCode(1679131549);

        $this->subject->createAdminUserWithRandomPassword('administrator');
    }
}

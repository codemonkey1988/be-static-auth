<?php

declare(strict_types=1);

/*
 * This file is part of the "be_static_auth" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\BeStaticAuth\Tests\Unit\Password;

use Codemonkey1988\BeStaticAuth\Password\RandomPasswordGenerator;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * @covers \Codemonkey1988\BeStaticAuth\Password\RandomPasswordGenerator
 */
class RandomPasswordGeneratorTest extends UnitTestCase
{
    private RandomPasswordGenerator $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = new RandomPasswordGenerator();
    }

    /**
     * @return list<array{length: int}>
     */
    public static function passwordLengthDataProvider(): array
    {
        return [
            [
                'length' => 10,
            ],
            [
                'length' => 30,
            ],
            [
                'length' => 60,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider passwordLengthDataProvider
     */
    public function generatedPasswordWillMatchGivenLength(int $length): void
    {
        $generatedPassword = $this->subject->generate($length);
        self::assertSame($length, strlen($generatedPassword));
    }
}

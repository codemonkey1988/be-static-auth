<?php

declare(strict_types=1);

/*
 * This file is part of the "be_static_auth" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Codemonkey1988\BeStaticAuth\Password;

final class RandomPasswordGenerator
{
    public function generate(int $length): string
    {
        if ($length < 2) {
            throw new \RuntimeException(
                sprintf('Given length must be >= 2, %d given.', $length),
                1679134020
            );
        }

        $triesLeft = 5;
        $generatedPassword = '';
        while ($triesLeft > 0 && strlen($generatedPassword) === 0) {
            try {
                /** @var int<1, max> $lengthForGeneration */
                $lengthForGeneration = (int)($length / 2);
                $generatedPassword = bin2hex(random_bytes($lengthForGeneration));
            } catch (\Exception $e) {
                $triesLeft--;
            }
        }

        if (strlen($generatedPassword) === 0) {
            throw new \RuntimeException('Could not generate random password.', 1622839550);
        }

        return $generatedPassword;
    }
}

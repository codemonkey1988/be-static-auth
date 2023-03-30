<?php

/*
 * This file is part of the "be_static_auth" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

$EM_CONF['be_static_auth'] = [
    'title' => 'Static Backend Login Provider',
    'description' => 'Adds a button to backend login that automatically creates an admin user and log in using it.',
    'category' => 'services',
    'author' => 'Tim Schreiner',
    'author_email' => 'dev@tim-schreiner.de',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'version' => '4.0.0-dev',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-12.3.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];

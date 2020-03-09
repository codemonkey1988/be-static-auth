<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Static Backend Login Provider',
    'description' => 'Adds a button to backend login that automatically creates an admin user and log in using it.',
    'category' => 'services',
    'author' => 'Tim Schreiner',
    'author_email' => 'schreiner.tim@gmail.com',
    'state' => 'beta',
    'clearCacheOnLoad' => true,
    'version' => '1.0.0-dev',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-10.3.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];

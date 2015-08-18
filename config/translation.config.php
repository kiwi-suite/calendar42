<?php
namespace Calendar42;

return [
    'translator'          => [
        'translation_file_patterns'    => [
            [
                'type'        => 'phparray',
                'base_dir'    => __DIR__ . '/../data/language/',
                'pattern'     => '%s.php',
                'text_domain' => 'admin',
            ],
        ],
    ],
];

<?php

return [
    /**
     * directory path to store cached Blade view files in
     */
    'cache_path'      => __DIR__ . '/../cache',

    /**
     * directory paths to find Blade view files in
     */
    'view_paths'      => [
        __DIR__ . '/../views',
    ],

    /**
     * file extensions to be treated as Blade view file
     */
    'view_extensions' => [
        'blade.php',
    ],
];

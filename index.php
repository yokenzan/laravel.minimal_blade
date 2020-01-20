<?php

use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;

require __DIR__ . '/vendor/autoload.php';

/*
 * directory path to store cached Blade view files in
 */
const CACHE_PATH          = 'cache';
/*
 * directory paths to find Blade view files in
 */
const VIEW_PATHS          = ['views'];
/*
 * file extension to be treated as Blade view file
 */
const TEMPLATE_EXTENSIONS = ['blade.php'];

/*
 * prepare View Factory instance
 */

$file     = new Filesystem();
$engine   = new CompilerEngine(new BladeCompiler($file, CACHE_PATH));
$resolver = new EngineResolver();
$resolver->register(
    'blade',
    function () use ($engine) {
        return $engine;
    }
);
$factory  = new Factory(
    $resolver,
    new FileViewFinder($file, VIEW_PATHS, TEMPLATE_EXTENSIONS),
    new Dispatcher(null)
);

/*
 * following proccess is equivalent to call
 * `view()` helper function in Laravel applications
 *
 * @link https://laravel.com/docs/6.x/helpers#method-view
 */

echo $factory
    ->make('hello_world', ['message' => 'Hello, World!'])
    ->render()
;

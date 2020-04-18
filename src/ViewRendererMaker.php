<?php

namespace MinimalBlade;

use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\FileEngine;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;

/**
 * The class which makes ViewRenderer instance by completing dependencies with default Illuminate objects.
 *
 * @link https://github.com/illuminate/view/blob/master/ViewServiceProvider.php
 */
class ViewRendererMaker
{
    /**
     * default directory path to store cached Blade view files in
     */
    private const CACHE_PATH = 'cache';
    /**
     * default directory paths to find Blade view files in
     */
    private const VIEW_PATHS = ['views'];

    /**
     * @var string   directory path to store cached Blade view files in
     */
    private $cachePath;
    /**
     * @var string[] directory paths to find Blade view files in
     */
    private $viewPaths;
    /**
     * @var string[]|null file extensions to be treated as Blade view file
     */
    private $viewExtensions;

    /**
     * @param string         $cachePath       directory path to store cached Blade view files in
     * @param string[]       $viewPaths       directory paths to find Blade view files in
     * @param string[]|null  $viewExtensions  file extensions to be treated as Blade view file
     */
    public function __construct(
        string  $cachePath = self::CACHE_PATH,
        array   $viewPaths = self::VIEW_PATHS,
        ?array  $viewExtensions = null
    ) {
        $this->cachePath      = $cachePath;
        $this->viewPaths      = $viewPaths;
        $this->viewExtensions = $viewExtensions;
    }

    /**
     * Makes ViewRenderer instance.
     */
    public function make(): ViewRenderer
    {
        /*
         * prepare View Factory instance
         */

        $engine  = $this->makeEngineResolver($this->cachePath);
        $finder  = $this->makeFileViewFinder($this->viewPaths, $this->viewExtensions);
        $factory = new Factory($engine, $finder, new Dispatcher(null));

        return new ViewRenderer($factory);
    }

    /**
     * Makes ViewRenderer instance from configuration values.
     *
     * @param (string|string[])[] $config
     */
    public function makeFromConfig(array $config): ViewRenderer
    {
        /*
         * apply configuration values
         */

        if (isset($config['cachePath'])) {
            $this->cachePath = $config['cache_path'];
        }

        if (isset($config['view_paths'])) {
            $this->viewPaths = $config['view_paths'];
        }

        if (isset($config['view_extensions'])) {
            $this->viewExtensions = $config['view_extensions'];
        }

        /*
         * make renderer
         */

        return $this->make();
    }

    /**
     * Make FileViewFinder instance.
     *
     * @param string[]      $viewPaths
     * @param string[]|null $viewExtensions
     */
    private function makeFileViewFinder(array $viewPaths, ?array $viewExtensions): FileViewFinder
    {
        return new FileViewFinder($this->makeFileSystem(), $viewPaths, $viewExtensions);
    }

    /**
     * Make EngineResolver instance.
     */
    private function makeEngineResolver(): EngineResolver
    {
        $resolver = new EngineResolver();

        $this->registerFileEngine($resolver);
        $this->registerPhpEngine($resolver);
        $this->registerBladeEngine($resolver);

        return $resolver;
    }

    /**
     * Makes Filesystem instance.
     */
    private function makeFileSystem(): Filesystem
    {
        return new Filesystem();
    }

    /**
     * Register the file engine implementation.
     *
     * @param  \Illuminate\View\Engines\EngineResolver  $resolver
     * @return void
     * @link https://github.com/illuminate/view/blob/master/ViewServiceProvider.php
     */
    private function registerFileEngine($resolver)
    {
        $resolver->register('file', function () {
            return new FileEngine();
        });
    }

    /**
     * Register the PHP engine implementation.
     *
     * @param  \Illuminate\View\Engines\EngineResolver  $resolver
     * @return void
     * @link https://github.com/illuminate/view/blob/master/ViewServiceProvider.php
     */
    private function registerPhpEngine($resolver)
    {
        $resolver->register('php', function () {
            return new PhpEngine();
        });
    }

    /**
     * Register the Blade engine implementation.
     *
     * @param  \Illuminate\View\Engines\EngineResolver  $resolver
     * @return void
     * @link https://github.com/illuminate/view/blob/master/ViewServiceProvider.php
     */
    private function registerBladeEngine($resolver)
    {
        $resolver->register('blade', function () {
            return new CompilerEngine(new BladeCompiler($this->makeFileSystem(), $this->cachePath));
        });
    }
}

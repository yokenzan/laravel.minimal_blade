<?php

namespace MinimalBlade;

use Illuminate\Contracts\View\Engine;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;

/**
 * The class which makes ViewRenderer instance by completing dependencies with default Illuminate objects.
 */
class ViewRendererMaker
{
    /**
     * default directory path to store cached Blade view files in
     */
    private const CACHE_PATH      = 'cache';
    /**
     * default directory paths to find Blade view files in
     */
    private const VIEW_PATHS      = ['views'];
    /**
     * default file extensions to be treated as Blade view file
     */
    private const VIEW_EXTENSIONS = ['blade.php'];


    /**
     * @var string   directory path to store cached Blade view files in
     */
    private $cachePath;
    /**
     * @var string[] directory paths to find Blade view files in
     */
    private $viewPaths;
    /**
     * @var string[] file extensions to be treated as Blade view file
     */
    private $viewExtensions;


    /**
     * @param string    $cachePath       directory path to store cached Blade view files in
     * @param string[]  $viewPaths       directory paths to find Blade view files in
     * @param string[]  $viewExtensions  file extensions to be treated as Blade view file
     */
    public function __construct(
        string  $cachePath      = self::CACHE_PATH,
        array   $viewPaths      = self::VIEW_PATHS,
        array   $viewExtensions = self::VIEW_EXTENSIONS
    )
    {
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

        if(isset($config['cachePath']))
            $this->cachePath      = $config['cache_path'];

        if(isset($config['view_paths']))
            $this->viewPaths      = $config['view_paths'];

        if(isset($config['view_extensions']))
            $this->viewExtensions = $config['view_extensions'];

        /*
         * make renderer
         */

        return $this->make();
    }

    /**
     * Makes FileViewFinder instance.
     *
     * @param string[] $viewPaths
     * @param string[] $viewExtensions
     */
    private function makeFileViewFinder(array $viewPaths, array $viewExtensions): FileViewFinder
    {
        return new FileViewFinder($this->makeFileSystem(), $viewPaths, $viewExtensions);
    }

    /**
     * Makes EngineResolver instance.
     *
     * @param string $cachePath
     */
    private function makeEngineResolver(string $cachePath): EngineResolver
    {
        $resolver = new EngineResolver();
        $resolver->register(
            'blade',
            function () use ($cachePath) {
                return $this->makeCompilerEngine($cachePath);
            }
        );
        return $resolver;
    }

    /**
     * Makes Engine instance.
     *
     * @param string $cachePath
     */
    private function makeCompilerEngine(string $cachePath): Engine
    {
        return new CompilerEngine(new BladeCompiler($this->makeFileSystem(), $cachePath));
    }

    /**
     * Makes Filesystem instance.
     */
    private function makeFileSystem(): Filesystem
    {
        return new Filesystem();
    }
}

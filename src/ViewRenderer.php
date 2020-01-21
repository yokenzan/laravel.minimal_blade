<?php

namespace MinimalBlade;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

/**
 * The wrapper of Laravel view Factory class.
 */
class ViewRenderer
{
    /**
     * @var Factory
     */
    private $factory;

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Get the evaluated view contents for the given view.
     *
     * This function is equivalent to call `view()` helper function in Laravel applications.
     *
     * @param  string  $view
     * @param  \Illuminate\Contracts\Support\Arrayable|array  $data
     * @param  array  $mergeData
     * @return \Illuminate\Contracts\View\View
     *
     * @link https://laravel.com/docs/6.x/helpers#method-view
     */
    public function make(string $view, $data = [], $mergeData = []): View
    {
        return $this->factory->make($view, $data, $mergeData);
    }

    /**
     * @param  string  $view
     * @param  \Illuminate\Contracts\Support\Arrayable|array  $data
     * @param  array  $mergeData
     * @return string
     */
    public function render(string $view, $data = [], $mergeData = []): string
    {
        return $this->make($view, $data, $mergeData)->render();
    }
}

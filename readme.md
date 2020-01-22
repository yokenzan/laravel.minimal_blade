# minimal source code to use Laravel Blade template engine

## About

This repository is a minimal source code to set up and use [Laravel](https://laravel.com/) [Blade template engine](https://laravel.com/docs/6.x/blade) for rendering view.

If you want to know what Laravel view components consist of, please see [Illuminate/View](https://github.com/illuminate/view) repository.


## Installation

You can install this repository by `git clone` command.

```sh
git clone git://github.com/yokenzan/laravel.minimal_blade.git laravel.minimal_blade
```

Or, write below text into `composer.json` and execute `composer install`.

```json:composer.json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/yokenzan/laravel.minimal_blade.git"
        }
    ],
    "require": {
        "yokenzan/minimal-blade": "dev-master"
    }
}
```


## How to use


You can get HTML output from Blade template by executing `MinimalBlade\ViewRenderer::make()`.

To get an instance of `MinimalBlade\ViewRenderer`, you can execute `MinimalBlade\ViewRendererMaker::make()`.

```php
<?php

require __DIR__ . '/vendor/autoload.php';

$config        = require __DIR__ . '/config/blade.php';
$rendererMaker = new MinimalBlade\ViewRendererMaker(
    $config['cache_path'], $config['view_paths'], $config['view_extensions']
);
$renderer      = $rendererMaker->make();

echo $renderer->render('hello', ['message' => 'Hello, World!']);
```

Or, also you can obtain an instance by executing `MinimalBlade\ViewRendererMaker::makeFromConfig()`.

```php
<?php

require __DIR__ . '/vendor/autoload.php';

$config        = [
    'view_paths'      => ['views', ],
    'view_extensions' => ['blade.php', ],
    'cache_path'      => 'cache',
];
$rendererMaker = new MinimalBlade\ViewRendererMaker();
$renderer      = $rendererMaker->makeFromConfig($config);

echo $renderer->render('hello', ['message' => 'Hello, World!']);
```

## License

[MIT License](https://opensource.org/licenses/mit-license.php)

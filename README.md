# Repository to try Laravel Blade template engine

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

You can get compiled HTML output from Blade template by executing `MinimalBlade\ViewRenderer::make()`.

To get an instance of `MinimalBlade\ViewRenderer`, you can execute `MinimalBlade\ViewRendererMaker::makeFromConfig()`.

```php
<?php

require __DIR__ . '/vendor/autoload.php';

$config        = [
    /* directory paths to find Blade view files in */
    'view_paths'      => ['views'],
    /* directory path to store compiled view cache files in */
    'cache_path'      => __DIR__ . '/cache',
    /* file extensions to be treated as Blade view file (optional) */
    'view_extensions' => [ 'blade.php'],
];

$rendererMaker = new MinimalBlade\ViewRendererMaker();
$renderer      = $rendererMaker->makeFromConfig($config);

echo $renderer->render('hello', ['message' => 'Hello, World!']);
```

When execute the PHP code above, you can see output like : 

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Minimal Config To Use Blade Template Engine</title>
</head>
<body>
    <p>Hello, World!</p>
</body>
</html>
```

## License

[MIT License](https://opensource.org/licenses/mit-license.php)

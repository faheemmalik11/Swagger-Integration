# Swagger Integration For Mystic-Forest-Maze

* [Installation](#installation)
* [Updating Controller](#updating_controller)

## Installation
Install the swagger package for laravel:
```sh
composer require "darkaonline/l5-swagger"
```
You can publish Swagger’s configuration and view files into your project by running the following command:
```sh
php artisan vendor:publish --provider  "L5Swagger\L5SwaggerServiceProvider"
```

## Updating Controller

After installing, you have to update your controller with swagger annotation that specifies info about oauth.
```sh
/**
 * @OA\Info(
 *    title="Mystic Forest Maze Apis",
 *    version="1.0.0",
 * )
 */
```



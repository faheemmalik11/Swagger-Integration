# Swagger Integration For Mystic-Forest-Maze

* [Installation](#installation)
* [Updating Controller](#updating-controller)
* [Annotation List](#annotation-list)

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

## Annotations list

| Name                  | Description                           | Places to use |
|-----------------------|---------------------------------------|---------------|
| @OA\Response          | Describes raw response                | Controller method |
| @OA\ResponseParam     | Describes response parameter in `Response` | Inside `{}` of `Response` annotation |
| @OA\ResponseClass     | Describes response as class object    | Controller method |
| @OA\ResponseError     | Describes error response (shortcut)   | Controller method |
| @OA\RequestBody       | Describes request body                | `FormRequest` class |
| @OA\RequestBodyJson   | Describes request body with `application\json` content type | `FormRequest` class |
| @OA\RequestParam      | Describes request body parameter      | Used as argument in `@OA\RequestBody` annotation |
| @OA\RequestParamArray | Describes request body parameter. Shortcut for array type parameter | Used as argument in `@OA\RequestBody` annotation |
| @OA\Parameter         | Describes route parameter             | Controller method, Controller class |
| @OA\Property          | Describes class property              | Class used for response |
| @OA\PropertyIgnore    | Mark class property as ignored        | Class used for response |
| @OA\Secured           | Describes route as secured            | Controller method |
| @OA\Tag               | Describes route tags                  | Controller method, Controller class |
| @OA\Ignore            | Marks whole controller or it's action as ignored | Controller method, Controller class |
| @OA\Symlink           | Describes symlink to another class    | Class used for response |

# Swagger Integration For Mystic-Forest-Maze

* [Installation](#installation)
* [Updating Controller](#updating-controller)
* [Annotation List](#annotation-list)
* [Explanation](#explanation)
* [Adding Annotations To Routes](#adding-annotations-to-routes)
* [Adding Authorization](#adding-authorization)
* [Generate Swagger](#generate-swagger)
* [Open the Documentation](#opening-the-documentation)
* [Screenshots](#screenshots)

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


## Explanation

### Responses
Responses are parsed only if explicitly documented by `@Annotation`. It must be placed in PHPDoc of **controller method** that route use.
RAW response:
```php
/**
 * Controller method PHPDoc
 *
 * @OA\Response(true,contentType="application/json",description="Boolean response")
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
```
JSON RAW response:
```php
/**
 * Controller method PHPDoc
 *
 * @OA\ResponseJson({"key":"value"},status=201,description="User data response")
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
```
or
```php
/**
 * Controller method PHPDoc
 *
 * @OA\ResponseJson({
 *      @OA\ResponseParam("key",type="string",example="value",description="Some parameter"),
 * },status=201,description="User data response")
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
```
Response from class properties:
```php
/**
 * Controller method PHPDoc
 *
 * @OA\ResponseClass("App\User",description="User model response")
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
```
In example above response data will be parsed from `App\User` PHPDoc.
1. `@property` descriptions (property name, type and description)
2. `@property-read` descriptions (if set `with` property in `ResponseClass` annotation)
3. `@OA\Property` annotations (property name, type, description, example etc.)

`@OA\ResponseClass` use cases,
first is standard use but with additional properties
```php
/**
 * @OA\ResponseClass("App\User",with={"profile"},status=201)
 */
```
As items list
```php
/**
 * @OA\ResponseClass("App\User",asList=true)
 */
```
As paged items list
```php
/**
 * @OA\ResponseClass("App\User",asPagedList=true)
 */
```

Error responses
```php
/**
 * @OA\ResponseError(403) // Forbidden
 * @OA\ResponseError(404) // Not found
 * @OA\ResponseError(422) // Validation error
 */
```
### Request bodies
Request data is parsed from `::rules()` method of `FormRequest` class, that used in controller method for the route and it's annotations (`@OA\RequestBody`, `@OA\RequestBodyJson`,  `@OA\RequestParam`).
From `::rules()` method we can obtain only name and type of parameter and suggest some example,
but if you want fully describe parameters of request body you must place appropriate annotations in `FormRequest` class for route.
#### Examples
```php
/**
 * @OA\RequestBodyJson({
 *   @OA\RequestParam("first_name",type="string",description="User name"),
 *   @OA\RequestParam("email",type="string",description="User email"),
 *   @OA\RequestParamArray("phones",items="string",description="User phones array"),
 * })
 */
```
### Tags
Tags can be defined in Controller class or method that route uses.
Do not use space ` ` in tag names, link with such tag name will be broken in Swagger UI, so better idea to use dash `-` or underscore `_`, or even just a `CamelCased` tag names.
Tags defined in controller will be applied to ALL controller methods.
```php
/**
 * @OA\Tag("Tag-name")
 */
```
### Secured
This annotation is used to mark route as `secured`, and tells to swagger, that you must provide valid user credentials to access this route.
Place it in controller method.
```php
/**
 * @OA\Secured()
 */
```
### Property
`@OA\Property` annotation is used to describe class properties as an alternative or addition to PHPDoc `@property`.
You can place example of property (if property is an associative array for example)
or fully describe property if you dont want to place `@property` declaration for it.
```php
/**
 * @OA\Property("notification_settings",type="object",example={"marketing":false,"user_actions":true},description="User notification settings")
 */
```
### PropertyIgnore
`@OA\PropertyIgnore` annotation is used to remove given property from object description.
```php
/**
 * @OA\PropertyIgnore("property_name")
 */

```

## Adding Annotations To Routes
Now we can start adding annotations to our apis. For example we add annotations to our login api in AuthController.php
```php

    /**
        * @OA\Post(
        * path="/administration/login",
        * operationId="authLogin",
        * tags={"Administration"},
        * summary="Administartion Login",
        * description="Login Administartion Here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"email", "password"},
        *               @OA\Property(property="email", type="email"),
        *               @OA\Property(property="password", type="password")
        *            ),
        *        ),
        *    ),
        *      
        *      @OA\Response(
        *          response=200,
        *          description="Login Successfully",
        *          @OA\JsonContent(            
        *               example={
        *                 
        *           "code": 200,
        *            "data": {
        *                "message": "Login successful",
        *                "user": {
        *                    "id": 1,
        *                    "name": "Maze Administration",
        *                    "email": "maze_administration@gmail.com",
        *                    "created_at": "2023-07-25T14:33:21.000000Z",
        *                    "updated_at": "2023-07-25T14:33:21.000000Z"
        *                },
        *                "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzUxMiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW5pc3RyYXRpb24vbG9naW4iLCJpYXQiOjE2OTAzNTk4NzYsImV4cCI6MTY5MDM3NDI3NiwibmJmIjoxNjkwMzU5ODc2LCJqdGkiOiJyaE5XYlBRdWlTUTR4azhtIiwic3ViIjoiMSIsInBydiI6ImI5OWExMWZkYzVmNWRiMDE5NjM2ZmVkODQ2NWUyZDlkYzQ4Yzg1YzYifQ.rAYX1lKYZ-5L0VCwm7Ked6zKnc9zuMygSVVwNJET-YtikLV9RJ_J5G5iZOpkDSdDLFIUIWlybJJKy1cUvv2ofyzUd9gS0JavOJJ3bpUi928NyYqxQtrQvaWmlEVt9NdcUCayGQmDCkuZvir_sYtqhv0or3cHtF02IAKaLTZ-d0SNVgDIrq4rSTF0SCCaWquKhr6NIPLMRUVvGxWKntlUapWv1WtQAS2rxqlJi6RCmI8ULB8tpHgN-ZNY2L5u5TD42_hVzzBVe5j0SwwVA9NrKVU1Gp0xyDIBLQgLISx5dgG-DWgugdeCdJ4rPxkma4nYWzZTs2rkjVG7KlfYNRdE5PRvYgEI2d7kWI8YkXqsPjUgQXvUy47bZT9wj9cij4bX81endH7ijfd6lYzV-yqgTYPgqwnAr0hl_euSjRDhDxz3KFpnsMaov-l4Eqo7TrBPcT4m5ScEB41ZKEb6moAdmwkCleh5OOmuEcRyEYqY_rtWn80HEtTjSBwo2CR0S4zYYN89R66r8p0-fpQlSJmeiYNl2yS0hvPwhE3Us9yYZbGrZc2fKWx7V65E6ppbZ3gp2RMQKB0GPGI6ApyZvjIRBC7wpNASBG_RLBZK4w8ER24oZu8YVC4e0wtg-rVkWRD5lYopD_Gf97LgwVSghcuMUbjIBgEWJSSGyUmheM1k4Fg",
        *                "timestamp": "2023-07-26T08:24:36.784868Z"
        *            }
        * 
        *       })
        *       ),
        *      @OA\Response(
        *          response=401,
        *          description="Unauthorized",
        *          @OA\JsonContent(            
        *               example={
        *                 
        *           "code": 401,
        *                "message": "Invalid credentials",
        *                "timestamp": "2023-07-26T08:24:36.784868Z"
        * 
        *       })
        *       ),


        * )
        */
```
Like this, we are going to add these annotations to other apis as well.


## Adding Authorization
We will also add the authorization in our swagger apis, so request could be authenticated first. For this to do we have to define secuirtScheme in swagger config file.
```sh
bearer_token' => [ // Unique name of security
                    'type' => 'apiKey', // Valid values are "basic", "apiKey" or "oauth2".
                    'description' => 'Enter token in format (Bearer token)',
                    'name' => 'Authorization', // The name of the header or query parameter to be used.
                    'in' => 'header', // The location of the API key. Valid values are "query" or "header".
                 ],
```
we will add this in the secuirtSchemes of swagger config file.

After adding this, we just have to add `security={{"bearer_token":{}}}` at the end of annotations of apis we want to secure.


## Generate Swagger
To generate the swagger documentation file just run php artisan l5-swagger: generate command.
```sh
php artisan l5-swagger:generate
```

## Open the Documentation
```sh
http://localhos/projectname/api/documentation
```
In our project it is:
```sh
http://127.0.0.1:8000/api/documentation
```

## Screenshots 

![Laravel OpenAPI 01](/mystic-forest-maze/public/Screenshot%20from%202023-07-27%2019-15-38.png)

![Laravel OpenAPI 02](/mystic-forest-maze/public/Screenshot%20from%202023-07-27%2019-15-57.png)

![Laravel Open 03](/mystic-forest-maze/public/Screenshot%20from%202023-07-27%2019-16-36.png)
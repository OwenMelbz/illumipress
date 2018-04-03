# IllumiPress

IllumiPress is a simple wrapper for the laravel 5 illuminate support, http request, response, validator and blade allowing it to be easily imported into any PHP 7+ project.

Initially designed to bring illuminate support to wordpress, which is how we get IllumiPress.

There is no WordPress specific code within the project, meaning this can be used for any PHP project.

## Installation 

The easiest way to install is via composer e.g `composer require owenmelbz/illumipress` from within your project root.

Even if your project is not using composer you can use the above command, but then you must manually include `vendor/autoload.php` in your project, for wordpress consider using the `functions.php`

## Features

As the illuminate packages require the illuminate/support package, you also get access to an array of magic such as

- collections via `collect`
- both `dump` and `dd`
- array helpers such as `data_get`, `array_wrap`, `array_dot` etc
- string helpers e.g `ends_with`, `starts_with`, `str_contains`, `str_random`
- logical helpers such as `optional`, `tap`, `throw_if`
- access to Guzzle via `kitetail\zttp` and a `http()` helper
- laravel blade template rendering using `filename.blade.php` allowing `view('component.sidebar')` etc

You can see a full list of included components https://github.com/illuminate/support

## Usage

The package has a child class which extends the core illuminate classes to add some additional helper functions.

## Request Class

This does not currently have any custom functionality, you should be able to use the documentation provided https://laravel.com/docs/5.6/requests - you have access to a global helper `request()` to get a new instance of the request object, so you can do things such as `request()->get('my_posted_data', 'default value')` etc.

## Response Class

The main difference between the laravel and this implimentation is that typically you must manually use symfonys "send()" method to complete the request e.g.

```
return response(['hello' => 'world'])->send();
```

There are however custom helpers on top to provide a consistent ajax style responses, `ajax(), success(), error()`

```
// alias of ->success()
// Sends a JSON response formatted into a JSONSchema'esque structure

return response([
	'hello' => 'world'
])->ajax();

// Returns 200 header
{
	"data": {"hello": "world"},
	"meta": {"success": true}
}

// You can also send a JSON response with error headers

return response('Sorry something went wrong')->error(422);

// Returns a 422 error
{
	"data": "Sorry something went wrong",
	"meta": {"success": false}
}
```

You can also add custom meta to the response, useful for things such as "next" and "prev" data.

```
return response('My response')->addMeta(['key' => 'value'])->send();
```

You can completely overwrite the meta using `setMeta` method.

## Validation class

This provides 2 extra methods on top of the validation class, firstly the ability to return a formatted list of errors using `$validator->formattedErrors()` and an ajax result using the previously mentioned automatic formatting by `$validator->response()`.

You have the the full validation class (https://laravel.com/docs/5.6/validation) under there to use such as

```
$validator = validator(request()->all(), [
	'name' => 'required',
	'email' => 'required|email'
]);

if ($validator->fails()) {
	return $validator->response();
}

// Outputs
{
	"data": [
		{
			"param": "name",
			"messages": [
				"the name field is required",
			]
		}
	],
	"meta": {
		"success": false
	}
}

```

By default we only include the default laravel i18n error messages, you can follow the laravel documentation for passing in custom messages https://laravel.com/docs/5.6/validation#custom-error-messages

If you need to use translations you can load your custom messages file e.g

```
$validator = validator($data, $rules);
$validator->setLanguageFile(__DIR__ . '/i18n/french.php');
```

## Laravel Blade

Blade has also been included to allow a more fluent syntax for rendering templates.

By default it is enabled - however you can turn it off by using `turn_blade_off()` and `turn_blade_on()`

If you name your files `template.blade.php` blade can render the template directly, however if you enable blade and have normal `template.php` files, it will create a dynamic copy within the `wp-content/uploads/.cache/` which will update each time you make a file change.

The integration is loosely based off https://github.com/tormjens/wp-blade which means you get some starter directives such as, `@post, @wpquery() @acf @acfhas @acffield @acfsub`

You can return a rendered view by using the `view('components.sidebar')` helper

## HTTP Client / Guzzle / cURL / zttp

We also include the `kitetail/zttp` library for a simple curl access via the `http()` helper, for full information we recommend checking out the zttp github documentation

```
$stringResponse = http('https:/www.google.com');
```

for a simple curl GET request you can use the above, for more complicated requests you can use the fuller syntax, which returns an instance of `ZttpResponse`

```
$response = http()->post('https://www.google.com/', ['q' => 'my query']);

if ($response->isOkay()) {
    echo $response->body();
    echo $response->json();
    echo etc...
}

``` 

As `zttp` uses Guzzle under the hood, which means you can also get access to the full guzzle suite and do things such as `(new Guzzle\Client)->setBaseUri('https://www.google.com/')->post('search', ['q' => 'query''])`;

## License
This is a completely free and open source project, feel free to use it for anything you like, if you wish to modify and redistribute it however, please give some credit back to the original repository.





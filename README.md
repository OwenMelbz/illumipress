# IllumiPress

IllumiPress is a simple wrapper for the laravel 5 illuminate http request, response and validator allowing it to be easily imported into any PHP 7+ project.

Initially designed to bring illuminate support to wordpress, which is how we get IllumiPress.

There is no WordPress specific code within the project, meaning this can be used for any PHP project.

## Installation 

The easiest way to install is via composer e.g `composer require owenmelbz/illumipress` from within your project root.

Even if your project is not using composer you can use the above command, but then you must manually include `vendor/autoload.php` in your project, for wordpress consider using the `functions.php`

## Usage

The package has a child class which extends the core illuminate classes to add some additional helper functions.

### Request Class

This does not currently have any custom functionality, you should be able to use the documentation provided https://laravel.com/docs/5.6/requests - you have access to a global helper `request()` to get a new instance of the request object, so you can do things such as "request()->get('my_posted_data', 'default value')" etc.

### Response Class

The main difference between the laravel and this implimentation is that typically you must manually use symfonys "send()" method to complete the request e.g.

```
return response(['hello' => 'world'])->send();
``

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

### Validation class

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

## License
This is a completely free and open source project, feel free to use it for anything you like, if you wish to modify and redistribute it however, please give some credit back to the original repository.





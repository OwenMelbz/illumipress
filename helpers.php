<?php

function request(array $query = null, array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
{
	if ($query === null) {
		$request = \OwenMelbz\IllumiPress\Request::createFromGlobals();
	} else {
		$request = new \OwenMelbz\IllumiPress\Request($query, $request, $attributes, $cookies, $files, $server, $content);
	}


	return $request;
}

function response($content = '', int $status = 200, array $headers = [])
{
	$response = new \OwenMelbz\IllumiPress\Response($content, $status, $headers);

	return $response;
}

function validator(array $data = [], array $rules = [], array $messages = [])
{
	$validator = new \OwenMelbz\IllumiPress\Validator($data, $rules, $messages);

	return $validator;
}

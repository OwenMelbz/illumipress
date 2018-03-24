<?php

function request(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
{
	$request = new \OwenMelbz\IllumiPress\Request($qery, $request, $attributes, $cookies, $files, $server, $content);

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

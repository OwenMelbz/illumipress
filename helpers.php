<?php

/**
 * @param array|null $query
 * @param array $request
 * @param array $attributes
 * @param array $cookies
 * @param array $files
 * @param array $server
 * @param null $content
 * @return \OwenMelbz\IllumiPress\Request
 */
function request(array $query = null, array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
{
    if ($query === null) {
        $request = \OwenMelbz\IllumiPress\Request::createFromGlobals();
    } else {
        $request = new \OwenMelbz\IllumiPress\Request($query, $request, $attributes, $cookies, $files, $server, $content);
    }


    return $request;
}

/**
 * @param string $content
 * @param int $status
 * @param array $headers
 * @return \OwenMelbz\IllumiPress\Response
 */
function response($content = '', int $status = 200, array $headers = [])
{
    $response = new \OwenMelbz\IllumiPress\Response($content, $status, $headers);

    return $response;
}

/**
 * @param array $data
 * @param array $rules
 * @param array $messages
 * @return \OwenMelbz\IllumiPress\Validator
 */
function validator(array $data = [], array $rules = [], array $messages = [])
{
    $validator = new \OwenMelbz\IllumiPress\Validator($data, $rules, $messages);

    return $validator;
}

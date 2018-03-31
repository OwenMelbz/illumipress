<?php

if (! function_exists('request')) {
    /**
     * Returns a fully compatible instance of an Illuminate\Http\Request with some extras
     *
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
}

if (! function_exists('response')) {
    /**
     * Returns a fully compatible instance of an Illuminate\Http\Response with some extras
     *
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
}

if (! function_exists('validator')) {
    /**
     * Returns a custom Validator class with access to the Illuminate\Validation\Validator class
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @return \OwenMelbz\IllumiPress\Validator
     */
    function validator(array $data = [], array $rules = [], array $messages = [], array $customAttributes = [])
    {
        $validator = new \OwenMelbz\IllumiPress\Validator($data, $rules, $messages, $customAttributes);

        return $validator;
    }
}

if (! function_exists('http')) {
    /**
     * @param array ...$args
     * @return \Zttp\PendingZttpRequest
     */
    function http(...$args)
    {
        return \Zttp\PendingZttpRequest::new(...$args);
    }
}

if (! function_exists('dump')) {
    /**
     * Dump the passed variables
     *
     * @param mixed $args
     * @return void
     */
    function dump(...$args)
    {
        foreach ($args as $x) {
            (new \Illuminate\Support\Debug\Dumper)->dump($x);
        }
    }
}

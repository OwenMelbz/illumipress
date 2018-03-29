<?php

namespace OwenMelbz\IllumiPress;

use Exception;
use Illuminate\Validation\Factory;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;

/**
 * Class Validator
 * @package OwenMelbz\IllumiPress
 */
class Validator
{

    /**
     * Provides access to the underlying validator
     *
     * @var \Illuminate\Validation\Validator
     */
    public $validator;

    /**
     * Stores the language strings for validation message replacement
     *
     * @var array
     */
    public $messages = [];

    /**
     * Creates a new instance of the IllumiPress Validator
     *
     * @param array $data
     * @param array $rules
     * @param array $messageArray
     */
    public function __construct(array $data = [], array $rules = [], array $messageArray = [], array $customAttributes = [])
    {
        $filesystem = new Filesystem();
        $fileLoader = new FileLoader($filesystem, '');
        $translator = new Translator($fileLoader, 'en_US');
        $factory = new Factory($translator);

        $illuminateMessages = include __DIR__ . '/i18n/en.php';
        $messages = array_merge($illuminateMessages, $this->messages, $messageArray);

        $this->validator = $factory->make($data, $rules, $messages, $customAttributes);

        return $this;
    }

    /**
     * Accepts a file path which loads in language strings for error message replacement
     *
     * @param $file
     * @return $this
     * @throws Exception
     */
    public function setLanguageFile($filePath)
    {
        if (!file_exists($filePath)) {
            throw new Exception('Language file does not exist');
        }

        $this->messages = include $filePath;

        $this->validator->setCustomMessages($this->messages);

        return $this;
    }

    /**
     * Returns a consistent format for errors which can be used on most frontend clients
     *
     * @return array
     */
    public function formattedErrors(array $messages = null)
    {
        $errors = [];
        $messages = $messages ?: $this->validator->errors()->getMessages();

        foreach ($messages as $field => $_messages) {
            $errors[] = [
                'param' => $field,
                'messages' => $_messages
            ];
        }

        return $errors;
    }

    /**
     * Dispatches either a positive or negative response based off the validation result
     *
     * @return Response
     * @param bool $return
     */
    public function ajax(bool $return = false)
    {
        if ($this->validator->passes()) {
            return response()->success(null, $return);
        }

        return response(
            $this->formattedErrors()
        )->error(422, $return);
    }

    /**
     * An alias for ajax()
     *
     * @return Response
     * @param bool $return
     */
    public function response(bool $return = false)
    {
        return $this->ajax($return);
    }

    /**
     * Allows you to easily call any underlying validator methods
     *
     * @param $method
     * @param $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        return call_user_func_array([$this->validator, $method], $args);
    }

}

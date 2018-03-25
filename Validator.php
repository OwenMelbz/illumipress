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
     * @var \Illuminate\Validation\Validator
     */
    protected $validator;

    /**
     * @var array
     */
    protected $messages = [];

    /**
     * Validator constructor.
     * @param array $data
     * @param array $rules
     * @param array $messageArray
     */
    public function __construct(array $data = [], array $rules = [], array $messageArray = [])
    {
        $filesystem = new Filesystem();
        $fileLoader = new FileLoader($filesystem, '');
        $translator = new Translator($fileLoader, 'en_US');
        $factory = new Factory($translator);

        $illuminateMessages = include __DIR__ . '/i18n/en.php';
        $messages = array_merge($illuminateMessages, $this->messages, $messageArray);

        $this->validator = $factory->make($data, $rules, $messages);

        return $this;
    }

    /**
     * @param $file
     * @return $this
     * @throws Exception
     */
    public function setLanguageFile($file)
    {
        if (!file_exists($file)) {
            throw new Exception('Language file does not exist');
        }

        $this->messages = include $file;

        return $this;
    }

    /**
     * @return array
     */
    public function formattedErrors()
    {
        $messages = [];

        foreach ($this->validator->errors()->getMessages() as $field => $messages) {
            $messages[] = [
                'param' => $field,
                'messages' => $messages
            ];
        }

        return $messages;
    }

    /**
     * @return Response
     */
    public function ajax()
    {
        if ($this->validator->passes()) {
            return response()->success(null);
        }

        return response(
            $this->formattedErrors()
        )->error(422);
    }

    /**
     * @return Response
     */
    public function response()
    {
        return $this->ajax();
    }

    /**
     * @param $method
     * @param $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        return call_user_func_array([$this->validator, $method], $args);
    }
}

<?php

namespace OwenMelbz\IllumiPress;

use Exception;
use Illuminate\Validation\Factory;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;

class Validator
{
	protected $validator;

	protected $messages = [];

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

    public function setLanguageFile($file)
    {
    	if (!file_exists($file)) {
    		throw new Exception('Language file does not exist');
    	}

    	$this->messages = include $file;

    	return $this;
    }

    public function formattedErrors()
    {
    	$messages = [];

        foreach($this->validator->errors()->getMessages() as $field => $errors) {
            $messages[] = [
                'param' => $field,
                'errors' => $errors
            ];
        }

        return $messages;
    }

    public function ajax()
    {
    	if ($this->validator->passes()) {
    		return response()->success(null);
    	}

    	return response(
    		$this->formattedErrors()
    	)->error(422);
    }

    public function response()
    {
    	return $this->ajax();
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->validator, $method], $args);
    }
}

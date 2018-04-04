<?php

use PHPUnit\Framework\TestCase;
use OwenMelbz\IllumiPress\Validator;

class ValidatorTest extends TestCase
{

    public function test__construct()
    {
        $validator = new Validator();

        $this->assertInstanceOf('OwenMelbz\IllumiPress\Validator', $validator);
        $this->assertInstanceOf('Illuminate\Validation\Validator', $validator->validator);
    }

    public function testSetLanguageFile()
    {
        $langFile = __DIR__ . '/../src/i18n/en.php';
        $validator = new Validator();

        try {
            $validator->setLanguageFile($langFile);
        } catch (Exception $e) {
        }

        $this->assertNotEmpty($validator->messages);
    }

    public function testFormattedErrors()
    {
        $validator = new Validator([], ['name' => 'required']);

        $expected = [
            [
                'param' => 'name',
                'messages' => [],
            ]
        ];

        $messages = $validator->formattedErrors();

        $this->assertArraySubset($expected, $messages);
        $this->assertNotEmpty($messages);
    }

    public function testFormattedCustomErrors()
    {
        $validator = new Validator([], ['name' => 'required']);

        $expected = [
            [
                'param' => 'name',
                'messages' => [],
            ]
        ];

        $messages = [
            'name' => [
                'some validation error'
            ]
        ];

        $messages = $validator->formattedErrors($messages);

        $this->assertArraySubset($expected, $messages);
        $this->assertNotEmpty($messages);
    }

    public function testAjax()
    {
        $validator = new Validator([], ['name' => 'required']);

        $response = $validator->ajax(true);

        $this->assertInstanceOf('OwenMelbz\IllumiPress\Response', $response);
        $this->assertEquals(422, $response->getStatusCode());
    }
}

<?php

use OwenMelbz\IllumiPress\Validator;
use PHPUnit\Framework\TestCase;

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

        $validator->setLanguageFile($langFile);

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

    public function testAjax()
    {
        $validator = new Validator([], ['name' => 'required']);

        $response = $validator->ajax(true);

        $this->assertInstanceOf('OwenMelbz\IllumiPress\Response', $response);
        $this->assertEquals(422, $response->getStatusCode());
    }
}

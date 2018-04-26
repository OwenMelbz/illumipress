<?php

use Encryption;
use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{

    public function testRequestHelper()
    {
        $this->assertInstanceOf('OwenMelbz\IllumiPress\Request', request());
    }

    public function testResponseHelper()
    {
        $this->assertInstanceOf('OwenMelbz\IllumiPress\Response', response());
    }

    public function testValidationHelper()
    {
        $this->assertInstanceOf('OwenMelbz\IllumiPress\Validator', validator());
    }

    public function testSupportHelpersLoaded()
    {
        $this->assertInstanceOf('Illuminate\Support\Collection', collect());
        $this->assertTrue(function_exists('dd'));
        $this->assertTrue(function_exists('dump'));
        $this->assertTrue(function_exists('str_contains'));
        $this->assertTrue(function_exists('kebab_case'));
        $this->assertTrue(function_exists('optional'));
        $this->assertTrue(function_exists('tap'));
        $this->assertTrue(function_exists('array_wrap'));
    }

    public function testCanMakeZttpCall()
    {
        $this->assertInstanceOf('Zttp\PendingZttpRequest', http());
        $this->assertInternalType('string', http('https://www.google.com'));
    }

    public function testWhoops()
    {
        $this->assertInstanceOf('Whoops\Run', turn_whoops_on());
        $this->assertInstanceOf('Whoops\Run', turn_whoops_off());
    }

    public function testEncryptionHelper()
    {
        $encryptor = encryption('hgtyuioplkmnbvcx');
        $secret = 'test';
        $encrypted = $encryptor->encrypt($secret);
        $decrypted = $encryptor->decrypt($encrypted);

        $this->assertInstanceOf('OwenMelbz\IllumiPress\Encryption', $encryptor);
        $this->assertEquals($secret, $decrypted);
    }
}

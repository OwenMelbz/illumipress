<?php

use PHPUnit\Framework\TestCase;
use OwenMelbz\IllumiPress\Response;

class ResponseTest extends TestCase
{

    public function testGetMeta()
    {
        $response = new Response();
        $response->meta = 'test meta';

        $this->assertEquals('test meta', $response->getMeta());
    }

    public function testSetMeta()
    {
        $response = new Response();

        $response->setMeta(['test' => 'meta']);

        $this->assertArrayHasKey('test', $response->meta);
        $this->assertEquals('meta', $response->meta['test']);
        $this->assertCount(1, $response->meta);
    }

    public function testAddMeta()
    {
        $response = new Response();
        $response->meta = [];

        $response->addMeta(['test' => 'meta']);
        $response->addMeta(['meta' => 'test']);

        $this->assertCount(2, $response->meta);
    }

    public function testAjax()
    {
        $response = new Response('hello world');
        $response->setMeta(['test' => 'test']);

        $result = $response->ajax(null, true)->getContent();

        $this->assertEquals('{"data":"hello world","meta":{"test":"test"}}', $result);
    }

    public function testSuccess()
    {
        $response = new Response('hello world');

        $result = $response->success(200, true)->getStatusCode();

        $this->assertEquals(200, $result);
    }

    public function testError()
    {
        $response = new Response('hello world');

        $result = $response->error(400, true)->getStatusCode();

        $this->assertEquals(400, $result);
    }
    
}

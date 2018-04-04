<?php

use PHPUnit\Framework\TestCase;
use OwenMelbz\IllumiPress\Blade;

class BladeTest extends TestCase
{
    public function testIsEnabled()
    {
        turn_blade_on();
        $bladeState = Blade::isEnabled();

        $this->assertTrue($bladeState);
    }

    public function testTurnOff()
    {
        $bladeState = turn_blade_off();

        $this->assertFalse($bladeState);
        $this->assertFalse(Blade::isEnabled());
    }

    public function testTurnOn()
    {
        $bladeState = turn_blade_on();

        $this->assertTrue($bladeState);
        $this->assertTrue(Blade::isEnabled());
    }

    public function testIwishItCouldBootWordPress()
    {
        $this->assertInternalType('string', 'One day, maybe we can boot up wordpress, or replace certain helpers with mocks');
    }

}

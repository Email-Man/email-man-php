<?php

use EmailMan\EmailMan;
use PHPUnit\Framework\TestCase;

Class EmailManTest extends TestCase {

    private $emailMan;

    protected function setUp():void {
        $this->emailMan = new EmailMan();
    }

    public function testValidateEmailAddress(){
        $response = $this->emailMan->validateAddress("test@email.com");
        $this->assertEquals(true, $response, "Validate Address Method Returned false");
    }
}
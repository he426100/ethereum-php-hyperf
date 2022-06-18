<?php

namespace Ethereum\Tests;

use Ethereum\PEMHelper;

class EthSignTest extends TestCase
{
    public function testVerify()
    {
        $address = '0x1c96099350f13d558464ec79b9be4445aa0ef579';
        $sign = '0x98d69c1067c80533451f9b84e949b0fd25e43d7bf3b308cb57c4a493b8fd5df11d9cbd9dcd0548716af1ea4b44c9d7228e9a59337b6c6e8eb40f693a5a90e31c1b';
        $message = 'Hello, world!';

        $this->assertEquals(PEMHelper::recoverPersonalSignature($message, $sign), $address);
        $this->assertTrue(PEMHelper::verifyPersonalSignature($message, $sign, $address));
    }
}

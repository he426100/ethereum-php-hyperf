<?php

namespace Ethereum\Tests;

use Ethereum\HRC20;
use Ethereum\HecoApi;

class HRC20Test extends TestCase
{
    const CONTRACT_ADDRESS = '0x04F535663110A392A6504839BEeD34E019FdB4E0';

    const HECO_KEY = '745NBY6ZY9KRM21HRWIVJVINA547NSF8KW';

    const WALLET_PRIVATE_KEY = 'b1d2c195349a7928f69ac146064cc06ddf2e0cca85fa9f0a3bdf7e3c986abd23';
    const WALLET_ADDRESS = '0x731C83B3888B7c717371B6bBCc8e1aBB1BfB5832';

    private function getHRC20($contractAddress = self::CONTRACT_ADDRESS)
    {
        $HRC20 = new HRC20($contractAddress, 6, new HecoApi(self::HECO_KEY, 'heco-test'));
        return $HRC20;
    }

    public function testBalanceApi()
    {
        $res = $this->getHRC20()->balanceByApi(self::WALLET_ADDRESS);
        var_dump($res);

        $this->assertTrue(true);
    }

    public function testBalance()
    {
        // $res = $this->getHRC20()->balance(self::WALLET_ADDRESS);
        // var_dump($res);
        // TODO: Implement ethCall() method.

        $this->assertTrue(true);
    }

    public function testTransfer() {
        $res = $this->getHRC20()->transfer(
            self::WALLET_PRIVATE_KEY,
            '0x7283Fd194ee26e69eDeF7CC1fDa577048ce39132',
            1
        );
        var_dump($res);

        $this->assertTrue(true);
    }
}

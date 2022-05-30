<?php

namespace Ethereum\Tests;

use Ethereum\ERC20;
use Ethereum\EtherscanApi;

class ERC20Test extends TestCase
{
    const CONTRACT_ADDRESS = '0x5dd74bf2b4e190e9fb535021225ad22e9d0ec30e';

    const INFURA_KEY = '8275f7b717754213a1c07e22939b324d';
    const ETHERSCAN_KEY = 'KJU6S4DP2AFPA91T6XEKCEDJUA6V5R9MD5';

    const WALLET_PRIVATE_KEY = '09b3d8965dc9ac93375750f253b48ea0672342c6b0ccf6110b78627851ef9f61';
    const WALLET_ADDRESS = '0x04d5b5a2fc54fc7336856ed55a56b0f20d5b9e54';

    private function getERC20($contractAddress = self::CONTRACT_ADDRESS)
    {
        $erc20 = new ERC20($contractAddress, 8, new EtherscanApi(self::ETHERSCAN_KEY, 'rinkeby'));
        return $erc20;
    }

    public function testBalanceApi()
    {
        $res = $this->getERC20()->balanceByApi(self::WALLET_ADDRESS);
        var_dump($res);

        $this->assertTrue(true);
    }

    public function testBalance()
    {
        $res = $this->getERC20()->balance(self::WALLET_ADDRESS);
        var_dump($res);

        $this->assertTrue(true);
    }

    public function testTransfer() {
        $res = $this->getERC20()->transfer(
            self::WALLET_PRIVATE_KEY,
            '0xcbec8ec09f94c80852e85693547f72b99ea2f327',
            1);
        var_dump($res);

        $this->assertTrue(true);
    }
}

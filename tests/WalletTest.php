<?php

namespace Ethereum\Tests;

use BIP\BIP44;
use Ethereum\Wallet;
use Ethereum\PEMHelper;
use FurqanSiddiqui\BIP39\BIP39;

class WalletTest extends TestCase
{
    function testGenerateANewEthereumPrivateKey()
    {
        $privateKey = PEMHelper::generateNewPrivateKey();
        $publicKey = PEMHelper::privateKeyToPublicKey($privateKey);
        $addressFromPrivate = PEMHelper::privateKeyToAddress($privateKey);

        echo "  Private Key: ".$privateKey."\n";
        echo "  Public Key:  ".$publicKey."\n";
        echo "  Address:     ".$addressFromPrivate."\n";

        $this->assertTrue(true);
    }

    public function testBIP44()
    {
        $mnemonic = BIP39::Words("glue country blast dash license flat weasel whip organ fun piano crazy");
        $seed = $mnemonic->generateSeed();

        $HDKey = BIP44::fromMasterSeed($seed)->derive("m/44'/60'/0'/0/0");

        $privateKey = $HDKey->privateKey;
        $this->assertEquals("e438f6c9d13dcc80734d2b22a73967359e11e9a1e0352501d62d0192c24e9f19", $privateKey);
    }

    public function testNewAccount()
    {
        $res1 = Wallet::newAccountByPrivateKey();
        var_dump($res1);

        $res2 = Wallet::newAccountByMnemonic('123456');
        var_dump($res2);

        $res3 = Wallet::revertAccountByMnemonic($res2['mnemonic'], '123456');
        var_dump($res3);

        $this->assertEquals($res3['key'], $res2['key']);
    }
}

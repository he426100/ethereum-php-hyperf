<?php
namespace Ethereum;

use Web3p\EthereumTx\Transaction;

class TransactionEvent
{
    public $transaction;
    public $privateKey;
    public $txHash;

    public function __construct(Transaction $transaction, string $privateKey, string $txHash)
    {
        $this->transaction = $transaction;
        $this->privateKey = $privateKey;
        $this->txHash = $txHash;
    }
}
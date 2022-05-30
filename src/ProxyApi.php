<?php

namespace Ethereum;

interface ProxyApi
{
    function getNetwork(): string;

    function send($method, $params = []);

    function gasPrice();

    function ethBalance(string $address);

    function receiptStatus(string $txHash): ?bool;

    function getTransactionReceipt(string $txHash);

    function sendRawTransaction($raw);

    function getNonce(string $address);

    function ethCall($params);

    function blockNumber();

    function getBlockByNumber(int $blockNumber);
}

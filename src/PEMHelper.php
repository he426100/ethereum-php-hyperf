<?php

namespace Ethereum;

use kornrunner\Keccak;
use Sop\CryptoTypes\Asymmetric\EC\ECPrivateKey;
use Sop\CryptoEncoding\PEM;

use InvalidArgumentException;
use RuntimeException;
use Elliptic\EC;


class PEMHelper
{
    /**
     * SHA3_NULL_HASH
     *
     * @const string
     */
    const SHA3_NULL_HASH = 'c5d2460186f7233c927e7db2dcc703c0e500b653ca82273b7bfad8045d85a470';

    /**
     * Generate a new Private / Public key pair
     * 
     * @return string
     */
    public static function generateNewPrivateKey()
    {

        $config = [
            'private_key_type' => OPENSSL_KEYTYPE_EC,
            'curve_name' => 'secp256k1'
        ];

        $res = openssl_pkey_new($config);
        if (!$res) {
            throw new RuntimeException(
                'ERROR: Failed to generate private key. -> ' . openssl_error_string()
            );
        }

        // Generate Private Key
        openssl_pkey_export($res, $priv_key);

        // Get The Public Key
        $priv_pem = PEM::fromString($priv_key);

        // Convert to Elliptic Curve Private Key Format
        $ec_priv_key = ECPrivateKey::fromPEM($priv_pem);

        // Then convert it to ASN1 Structure
        $ec_priv_seq = $ec_priv_key->toASN1();

        // Private Key & Public Key in HEX
        $priv_key_hex = bin2hex($ec_priv_seq->at(1)->asOctetString()->string());

        return $priv_key_hex;
    }

    /**
     * Generate the Address of the provided Public key
     * 
     * @param string $publicKey 
     * 
     * @return string
     */
    public static function publicKeyToAddress(string $publicKey)
    {
        if (Utils::isHex($publicKey) === false) {
            throw new InvalidArgumentException('Invalid public key format.');
        }
        $publicKey = Utils::stripZero($publicKey);
        if (strlen($publicKey) !== 130) {
            throw new InvalidArgumentException('Invalid public key length.');
        }
        return '0x' . substr(self::sha3(substr(hex2bin($publicKey), 1)), 24);
    }

    /**
     * Generate the Address of the provided Private key
     * 
     * @param string $privateKey 
     * 
     * @return string
     */
    public static function privateKeyToAddress(string $privateKey)
    {
        return self::publicKeyToAddress(
            self::privateKeyToPublicKey($privateKey)
        );
    }

    /**
     * Generate the Public key for provided Private key
     * 
     * @param string $privateKey Private Key
     * 
     * @return string
     */
    public static function privateKeyToPublicKey(string $privateKey)
    {
        if (Utils::isHex($privateKey) === false) {
            throw new InvalidArgumentException('Invalid private key format.');
        }
        $privateKey = Utils::stripZero($privateKey);

        if (strlen($privateKey) !== 64) {
            throw new InvalidArgumentException('Invalid private key length.');
        }

        $secp256k1 = new EC('secp256k1');
        $privateKey = $secp256k1->keyFromPrivate($privateKey, 'hex');
        $publicKey = $privateKey->getPublic(false, 'hex');

        return '0x' . $publicKey;
    }

    /**
     * Get sha3
     * keccak256
     *
     * @param string $value
     *
     * @return string
     */
    public static function sha3(string $value)
    {
        $hash = Keccak::hash($value, 256);
        // null sha
        if ($hash === self::SHA3_NULL_HASH) {
            return null;
        }
        return $hash;
    }

    /**
     * hasPersonalMessage
     *
     * @param string $message
     * @return string
     */
    public static function hashPersonalMessage(string $message)
    {
        $prefix = sprintf("\x19Ethereum Signed Message:\n%d", mb_strlen($message));
        return self::sha3($prefix . $message);
    }

    /**
     * verifyPersonalSignature
     *
     * @param string $message
     * @param string $sign
     * @param string $adress
     * @return bool
     */
    public static function verifyPersonalSignature(string $message, string $sign, string $adress): bool
    {
        $publicAddress = self::recoverPersonalSignature($message, $sign);
        return strtolower($publicAddress) == strtolower($adress);
    }

    /**
     * recoverPersonalSignature
     * @param string $message 
     * @param string $sign 
     * @return string 
     * @throws InvalidArgumentException 
     * @throws Exception 
     * @throws DivisionByZeroError 
     */
    public static function recoverPersonalSignature(string $message, string $signature)
    {
        if (strlen($signature) !== 132) {
            throw new InvalidArgumentException('Invalid signature length.');
        }
        $sign = [
            'r' => substr($signature, 2, 64),
            's' => substr($signature, 66, 64)
        ];
        if (strlen($sign['r']) !== 64 || strlen($sign['s']) !== 64) {
            throw new InvalidArgumentException('Invalid signature length.');
        }
        $recid  = ord(hex2bin(substr($signature, 130, 2))) - 27;
        if ($recid != ($recid & 1)) {
            throw new InvalidArgumentException('Invalid signature.');
        }
        $hash = self::hashPersonalMessage($message);
        $secp256k1 = new EC('secp256k1');
        $publicKey = $secp256k1->recoverPubKey($hash, $sign, $recid);
        $publicKey = $publicKey->encode('hex');
        return self::publicKeyToAddress($publicKey);
    }
}

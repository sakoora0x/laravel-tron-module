<?php

namespace sakoora0x\LaravelTronModule\Support;

use Elliptic\EC;
use InvalidArgumentException;

class Key
{
    /**
     * Generate the Address of the provided Public key
     *
     * @param string $publicKey
     *
     * @return string
     */
    public static function publicKeyToAddress(string $publicKey): string
    {
        if (Utils::isHex($publicKey) === false) {
            throw new InvalidArgumentException('Invalid public key format.');
        }
        $publicKey = Utils::stripZero($publicKey);
        if (strlen($publicKey) !== 130) {
            throw new InvalidArgumentException('Invalid public key length.');
        }
        return substr(Utils::sha3(substr(hex2bin($publicKey), 1)), 24);
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

        return $publicKey;
    }
}

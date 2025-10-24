<?php

use sakoora0x\LaravelTronModule\Support\Key;

describe('Key', function () {
    describe('privateKeyToPublicKey', function () {
        it('generates public key from valid private key', function () {
            $privateKey = '0x1234567890123456789012345678901234567890123456789012345678901234';
            $publicKey = Key::privateKeyToPublicKey($privateKey);

            expect($publicKey)->toBeString();
            expect(strlen($publicKey))->toBe(130);
        });

        it('throws exception for invalid hex format', function () {
            $privateKey = 'invalid_hex_string';

            expect(fn() => Key::privateKeyToPublicKey($privateKey))
                ->toThrow(InvalidArgumentException::class, 'Invalid private key format.');
        });

        it('throws exception for invalid private key length', function () {
            $privateKey = '0x1234'; // Too short

            expect(fn() => Key::privateKeyToPublicKey($privateKey))
                ->toThrow(InvalidArgumentException::class, 'Invalid private key length.');
        });

        it('works without 0x prefix', function () {
            $privateKey = '1234567890123456789012345678901234567890123456789012345678901234';
            $publicKey = Key::privateKeyToPublicKey($privateKey);

            expect($publicKey)->toBeString();
            expect(strlen($publicKey))->toBe(130);
        });
    });

    describe('publicKeyToAddress', function () {
        it('generates address from valid public key', function () {
            $publicKey = '04' . str_repeat('a', 128);
            $address = Key::publicKeyToAddress($publicKey);

            expect($address)->toBeString();
            expect(strlen($address))->toBe(40); // Hex string, 20 bytes = 40 characters
        });

        it('throws exception for invalid public key format', function () {
            $publicKey = 'not_valid_hex';

            expect(fn() => Key::publicKeyToAddress($publicKey))
                ->toThrow(InvalidArgumentException::class, 'Invalid public key format.');
        });

        it('throws exception for invalid public key length', function () {
            $publicKey = '0x1234';

            expect(fn() => Key::publicKeyToAddress($publicKey))
                ->toThrow(InvalidArgumentException::class, 'Invalid public key length.');
        });
    });

    describe('privateKeyToAddress', function () {
        it('generates address from valid private key', function () {
            $privateKey = '0x1234567890123456789012345678901234567890123456789012345678901234';
            $address = Key::privateKeyToAddress($privateKey);

            expect($address)->toBeString();
            expect(strlen($address))->toBe(40); // Hex string, 20 bytes = 40 characters
        });
    });
});

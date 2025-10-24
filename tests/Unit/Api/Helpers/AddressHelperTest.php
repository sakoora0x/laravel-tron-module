<?php

use sakoora0x\LaravelTronModule\Api\Helpers\AddressHelper;

describe('AddressHelper', function () {
    describe('toHex', function () {
        it('returns hex address if already in hex format', function () {
            $hexAddress = '41' . str_repeat('a', 40);
            $result = AddressHelper::toHex($hexAddress);

            expect($result)->toBe($hexAddress);
        });

        it('converts base58 address to hex', function () {
            // Known test vector: Base58 address
            $base58Address = 'TJRyWwFs9wTFGZg3JbrVriFbNfCug5tDeC';
            $result = AddressHelper::toHex($base58Address);

            expect($result)->toBeString();
            expect(strlen($result))->toBe(42);
            expect(str_starts_with($result, '41'))->toBeTrue();
        });
    });

    describe('toBase58', function () {
        it('returns base58 address if not hex', function () {
            $base58Address = 'TJRyWwFs9wTFGZg3JbrVriFbNfCug5tDeC';
            $result = AddressHelper::toBase58($base58Address);

            expect($result)->toBe($base58Address);
        });

        it('converts hex address to base58', function () {
            $hexAddress = '41' . str_repeat('a', 40);
            $result = AddressHelper::toBase58($hexAddress);

            expect($result)->toBeString();
            expect($result)->not()->toBe($hexAddress);
        });

        it('returns empty string for invalid hex', function () {
            $invalidHex = '41a'; // Odd length
            $result = AddressHelper::toBase58($invalidHex);

            expect($result)->toBe('');
        });
    });
});

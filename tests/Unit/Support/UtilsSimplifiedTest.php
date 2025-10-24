<?php

use sakoora0x\LaravelTronModule\Support\Utils;

describe('Utils (Simplified)', function () {
    describe('isHex', function () {
        it('returns true for valid hex string', function () {
            expect(Utils::isHex('0x1234abcd'))->toBeTrue();
            expect(Utils::isHex('1234ABCD'))->toBeTrue();
            expect(Utils::isHex('0xabc123'))->toBeTrue();
        });

        it('returns false for invalid hex string', function () {
            expect(Utils::isHex('xyz123'))->toBeFalse();
            expect(Utils::isHex('0xgh'))->toBeFalse();
        });
    });

    describe('isZeroPrefixed', function () {
        it('returns true for 0x prefixed strings', function () {
            expect(Utils::isZeroPrefixed('0x1234'))->toBeTrue();
            expect(Utils::isZeroPrefixed('0xabcd'))->toBeTrue();
        });

        it('returns false for non-prefixed strings', function () {
            expect(Utils::isZeroPrefixed('1234'))->toBeFalse();
            expect(Utils::isZeroPrefixed('abcd'))->toBeFalse();
        });
    });

    describe('stripZero', function () {
        it('removes 0x prefix', function () {
            expect(Utils::stripZero('0x1234'))->toBe('1234');
            expect(Utils::stripZero('0xabcd'))->toBe('abcd');
        });

        it('returns string unchanged if no prefix', function () {
            expect(Utils::stripZero('1234'))->toBe('1234');
            expect(Utils::stripZero('abcd'))->toBe('abcd');
        });
    });

    describe('isNegative', function () {
        it('returns true for negative numbers', function () {
            expect(Utils::isNegative('-123'))->toBeTrue();
            expect(Utils::isNegative('-0.5'))->toBeTrue();
        });

        it('returns false for positive numbers', function () {
            expect(Utils::isNegative('123'))->toBeFalse();
            expect(Utils::isNegative('0.5'))->toBeFalse();
        });
    });

    describe('isAddress', function () {
        it('returns true for valid addresses', function () {
            $validAddress = '0x' . str_repeat('a', 40);
            expect(Utils::isAddress($validAddress))->toBeTrue();

            $validAddress2 = str_repeat('A', 40);
            expect(Utils::isAddress($validAddress2))->toBeTrue();
        });

        it('returns false for invalid addresses', function () {
            expect(Utils::isAddress('0x123'))->toBeFalse();
            expect(Utils::isAddress('not_an_address'))->toBeFalse();
        });
    });

    describe('hexToBin', function () {
        it('converts hex string to binary', function () {
            $hex = '48656c6c6f'; // "Hello" in hex
            $result = Utils::hexToBin($hex);
            expect($result)->toBe('Hello');
        });

        it('handles 0x prefixed hex strings', function () {
            $hex = '0x48656c6c6f';
            $result = Utils::hexToBin($hex);
            expect($result)->toBe('Hello');
        });
    });

    describe('sha3', function () {
        it('computes keccak256 hash', function () {
            $result = Utils::sha3('test');
            expect($result)->toBeString();
            expect(strlen($result))->toBe(64);
        });

        it('handles hex input', function () {
            $result = Utils::sha3('0x1234');
            expect($result)->toBeString();
            expect(strlen($result))->toBe(64);
        });
    });
});

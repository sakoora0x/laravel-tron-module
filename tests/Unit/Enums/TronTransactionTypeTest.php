<?php

use sakoora0x\LaravelTronModule\Enums\TronTransactionType;

describe('TronTransactionType', function () {
    it('has INCOMING case with value "in"', function () {
        expect(TronTransactionType::INCOMING->value)->toBe('in');
    });

    it('has OUTGOING case with value "out"', function () {
        expect(TronTransactionType::OUTGOING->value)->toBe('out');
    });

    it('can be created from value', function () {
        $incoming = TronTransactionType::from('in');
        $outgoing = TronTransactionType::from('out');

        expect($incoming)->toBe(TronTransactionType::INCOMING);
        expect($outgoing)->toBe(TronTransactionType::OUTGOING);
    });

    it('returns all cases', function () {
        $cases = TronTransactionType::cases();

        expect($cases)->toHaveCount(2);
        expect($cases)->toContain(TronTransactionType::INCOMING);
        expect($cases)->toContain(TronTransactionType::OUTGOING);
    });
});

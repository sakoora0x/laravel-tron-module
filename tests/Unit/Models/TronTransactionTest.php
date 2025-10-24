<?php

use sakoora0x\LaravelTronModule\Models\TronTransaction;
use sakoora0x\LaravelTronModule\Enums\TronTransactionType;

describe('TronTransaction', function () {
    it('can create a transaction', function () {
        $transaction = TronTransaction::create([
            'txid' => 'test123',
            'address' => 'TTest123',
            'type' => TronTransactionType::INCOMING,
            'time_at' => now(),
            'from' => 'TFrom123',
            'to' => 'TTo123',
            'amount' => '1000000',
            'debug_data' => [],
        ]);

        expect($transaction)->toBeInstanceOf(TronTransaction::class);
        expect($transaction->txid)->toBe('test123');
        expect($transaction->address)->toBe('TTest123');
    });

    it('has no timestamps', function () {
        $transaction = new TronTransaction();

        expect($transaction->timestamps)->toBeFalse();
    });

    it('has fillable attributes', function () {
        $fillable = [
            'txid',
            'address',
            'type',
            'time_at',
            'from',
            'to',
            'amount',
            'trc20_contract_address',
            'block_number',
            'debug_data',
        ];

        $transaction = new TronTransaction();

        expect($transaction->getFillable())->toBe($fillable);
    });

    it('casts type to TronTransactionType enum', function () {
        $transaction = new TronTransaction();
        $casts = $transaction->getCasts();

        expect($casts)->toHaveKey('type');
    });

    it('casts attributes correctly', function () {
        $transaction = new TronTransaction();
        $casts = $transaction->getCasts();

        expect($casts)->toHaveKey('time_at', 'datetime');
        expect($casts)->toHaveKey('block_number', 'integer');
        expect($casts)->toHaveKey('debug_data', 'json');
    });

    it('has symbol attribute for TRX transactions', function () {
        $transaction = new TronTransaction([
            'txid' => 'test123',
            'trc20_contract_address' => null,
        ]);

        expect($transaction->symbol)->toBe('TRX');
    });

    it('has many addresses relation', function () {
        $transaction = new TronTransaction();

        expect($transaction->addresses())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
    });

    it('has many wallets through addresses', function () {
        $transaction = new TronTransaction();

        expect($transaction->wallets())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasManyThrough::class);
    });

    it('belongs to trc20 contract', function () {
        $transaction = new TronTransaction();

        expect($transaction->trc20())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    });
});

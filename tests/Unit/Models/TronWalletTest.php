<?php

use sakoora0x\LaravelTronModule\Models\TronWallet;
use sakoora0x\LaravelTronModule\Models\TronNode;
use sakoora0x\LaravelTronModule\Models\TronAddress;

describe('TronWallet', function () {
    it('can create a wallet', function () {
        $wallet = TronWallet::create([
            'name' => 'test-wallet',
            'title' => 'Test Wallet',
        ]);

        expect($wallet)->toBeInstanceOf(TronWallet::class);
        expect($wallet->name)->toBe('test-wallet');
        expect($wallet->title)->toBe('Test Wallet');
    });

    it('has fillable attributes', function () {
        $fillable = [
            'node_id',
            'name',
            'title',
            'password',
            'mnemonic',
            'seed',
            'sync_at',
            'balance',
            'trc20'
        ];

        $wallet = new TronWallet();

        expect($wallet->getFillable())->toBe($fillable);
    });

    it('hides sensitive attributes', function () {
        $wallet = new TronWallet();
        $hidden = $wallet->getHidden();

        expect($hidden)->toContain('password');
        expect($hidden)->toContain('mnemonic');
        expect($hidden)->toContain('seed');
        expect($hidden)->toContain('trc20');
    });

    it('casts attributes correctly', function () {
        $wallet = new TronWallet();
        $casts = $wallet->getCasts();

        expect($casts)->toHaveKey('password');
        expect($casts)->toHaveKey('mnemonic');
        expect($casts)->toHaveKey('seed');
        expect($casts)->toHaveKey('sync_at', 'datetime');
        expect($casts)->toHaveKey('trc20', 'json');
    });

    it('has has_password attribute', function () {
        $wallet = new TronWallet(['name' => 'test']);
        expect($wallet->has_password)->toBeFalse();

        $walletWithPassword = new TronWallet([
            'name' => 'test',
            'password' => 'secret123'
        ]);
        expect($walletWithPassword->has_password)->toBeTrue();
    });

    it('has has_mnemonic attribute', function () {
        $wallet = new TronWallet(['name' => 'test']);
        expect($wallet->has_mnemonic)->toBeFalse();

        $walletWithMnemonic = new TronWallet([
            'name' => 'test',
            'mnemonic' => 'word1 word2 word3'
        ]);
        expect($walletWithMnemonic->has_mnemonic)->toBeTrue();
    });

    it('has has_seed attribute', function () {
        $wallet = new TronWallet(['name' => 'test']);
        expect($wallet->has_seed)->toBeFalse();

        $walletWithSeed = new TronWallet([
            'name' => 'test',
            'seed' => 'seed123'
        ]);
        expect($walletWithSeed->has_seed)->toBeTrue();
    });

    it('can unlock wallet with password', function () {
        $wallet = TronWallet::create([
            'name' => 'test-wallet',
            'password' => 'secret123'
        ]);

        $wallet->unlockWallet('secret123');

        expect($wallet->plain_password)->toBe('secret123');
    });

    it('belongs to a node', function () {
        $wallet = new TronWallet();

        expect($wallet->node())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    });

    it('has many addresses', function () {
        $wallet = new TronWallet();

        expect($wallet->addresses())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
    });

    it('has many transactions through addresses', function () {
        $wallet = new TronWallet();

        expect($wallet->transactions())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasManyThrough::class);
    });

    it('has many deposits', function () {
        $wallet = new TronWallet();

        expect($wallet->deposits())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
    });
});

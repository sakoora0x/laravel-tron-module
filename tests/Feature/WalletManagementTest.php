<?php

use sakoora0x\LaravelTronModule\Models\TronWallet;
use sakoora0x\LaravelTronModule\Models\TronAddress;
use sakoora0x\LaravelTronModule\Models\TronNode;

describe('Wallet Management', function () {
    beforeEach(function () {
        // Setup test data
        $this->node = TronNode::create([
            'name' => 'test-node',
            'title' => 'Test Node',
            'full_node' => ['url' => 'https://api.trongrid.io'],
            'solidity_node' => ['url' => 'https://api.trongrid.io'],
            'block_number' => 0,
            'available' => true,
        ]);
    });

    it('can create a wallet with a node', function () {
        $wallet = TronWallet::create([
            'node_id' => $this->node->id,
            'name' => 'test-wallet',
            'title' => 'Test Wallet',
        ]);

        expect($wallet->node_id)->toBe($this->node->id);
        expect($wallet->node->name)->toBe('test-node');
    });

    it('can create addresses for a wallet', function () {
        $wallet = TronWallet::create([
            'node_id' => $this->node->id,
            'name' => 'test-wallet',
        ]);

        $address1 = TronAddress::create([
            'wallet_id' => $wallet->id,
            'address' => 'TTest123',
            'index' => 0,
        ]);

        $address2 = TronAddress::create([
            'wallet_id' => $wallet->id,
            'address' => 'TTest456',
            'index' => 1,
        ]);

        expect($wallet->addresses)->toHaveCount(2);
        expect($wallet->addresses->first()->address)->toBe('TTest123');
    });

    it('can manage wallet password', function () {
        $wallet = TronWallet::create([
            'name' => 'secure-wallet',
            'password' => 'my-secret-password',
        ]);

        expect($wallet->has_password)->toBeTrue();

        $wallet->unlockWallet('my-secret-password');

        expect($wallet->plain_password)->toBe('my-secret-password');
    });

    it('can track wallet balance', function () {
        $wallet = TronWallet::create([
            'name' => 'balance-wallet',
            'balance' => '100.5',
        ]);

        expect($wallet->balance)->not->toBeNull();
    });

    it('can have TRC20 balances', function () {
        $wallet = TronWallet::create([
            'name' => 'trc20-wallet',
            'trc20' => [
                'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t' => '1000.0'
            ],
        ]);

        expect($wallet->trc20)->toBeArray();
        expect($wallet->trc20)->toHaveKey('TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t');
    });
});

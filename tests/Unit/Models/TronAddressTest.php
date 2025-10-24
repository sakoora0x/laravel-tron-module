<?php

use sakoora0x\LaravelTronModule\Models\TronAddress;
use sakoora0x\LaravelTronModule\Models\TronWallet;

describe('TronAddress', function () {
    beforeEach(function () {
        $this->wallet = \sakoora0x\LaravelTronModule\Models\TronWallet::create([
            'name' => 'test-wallet',
        ]);
    });

    it('can create an address', function () {
        $address = TronAddress::create([
            'wallet_id' => $this->wallet->id,
            'address' => 'TJRyWwFs9wTFGZg3JbrVriFbNfCug5tDeC',
            'title' => 'Test Address',
        ]);

        expect($address)->toBeInstanceOf(TronAddress::class);
        expect($address->address)->toBe('TJRyWwFs9wTFGZg3JbrVriFbNfCug5tDeC');
        expect($address->title)->toBe('Test Address');
    });

    it('has fillable attributes', function () {
        $fillable = [
            'wallet_id',
            'address',
            'title',
            'watch_only',
            'private_key',
            'index',
            'sync_at',
            'activated',
            'balance',
            'trc20',
            'account',
            'account_resources',
            'touch_at',
            'available',
        ];

        $address = new TronAddress();

        expect($address->getFillable())->toBe($fillable);
    });

    it('hides sensitive attributes', function () {
        $address = new TronAddress();
        $hidden = $address->getHidden();

        expect($hidden)->toContain('private_key');
        expect($hidden)->toContain('trc20');
    });

    it('casts attributes correctly', function () {
        $address = new TronAddress();
        $casts = $address->getCasts();

        expect($casts)->toHaveKey('watch_only', 'boolean');
        expect($casts)->toHaveKey('sync_at', 'datetime');
        expect($casts)->toHaveKey('activated', 'boolean');
        expect($casts)->toHaveKey('trc20', 'json');
        expect($casts)->toHaveKey('account', 'json');
        expect($casts)->toHaveKey('account_resources', 'json');
        expect($casts)->toHaveKey('touch_at', 'datetime');
        expect($casts)->toHaveKey('available', 'boolean');
    });

    it('can be watch only', function () {
        $address = TronAddress::create([
            'wallet_id' => $this->wallet->id,
            'address' => 'TTest123',
            'watch_only' => true,
        ]);

        expect($address->watch_only)->toBeTrue();
    });

    it('can be activated', function () {
        $address = TronAddress::create([
            'wallet_id' => $this->wallet->id,
            'address' => 'TTest123',
            'activated' => true,
        ]);

        expect($address->activated)->toBeTrue();
    });

    it('can be available', function () {
        $address = TronAddress::create([
            'wallet_id' => $this->wallet->id,
            'address' => 'TTest123',
            'available' => true,
        ]);

        expect($address->available)->toBeTrue();
    });

    it('belongs to a wallet', function () {
        $address = new TronAddress();

        expect($address->wallet())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    });

    it('has many transactions', function () {
        $address = new TronAddress();

        expect($address->transactions())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
    });

    it('has many deposits', function () {
        $address = new TronAddress();

        expect($address->deposits())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
    });
});

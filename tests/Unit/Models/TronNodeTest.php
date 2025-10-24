<?php

use sakoora0x\LaravelTronModule\Models\TronNode;
use sakoora0x\LaravelTronModule\Api\Api;

describe('TronNode', function () {
    it('can create a node', function () {
        $node = TronNode::create([
            'name' => 'test-node',
            'title' => 'Test Node',
            'full_node' => ['url' => 'https://api.trongrid.io'],
            'solidity_node' => ['url' => 'https://api.trongrid.io'],
            'block_number' => 0,
        ]);

        expect($node)->toBeInstanceOf(TronNode::class);
        expect($node->name)->toBe('test-node');
        expect($node->title)->toBe('Test Node');
    });

    it('has no timestamps', function () {
        $node = new TronNode();

        expect($node->timestamps)->toBeFalse();
    });

    it('has fillable attributes', function () {
        $fillable = [
            'name',
            'title',
            'full_node',
            'solidity_node',
            'block_number',
            'requests',
            'requests_at',
            'sync_at',
            'worked',
            'available',
        ];

        $node = new TronNode();

        expect($node->getFillable())->toBe($fillable);
    });

    it('casts attributes correctly', function () {
        $node = new TronNode();
        $casts = $node->getCasts();

        expect($casts)->toHaveKey('full_node', 'json');
        expect($casts)->toHaveKey('solidity_node', 'json');
        expect($casts)->toHaveKey('block_number', 'integer');
        expect($casts)->toHaveKey('requests', 'integer');
        expect($casts)->toHaveKey('requests_at', 'date');
        expect($casts)->toHaveKey('sync_at', 'datetime');
        expect($casts)->toHaveKey('worked', 'boolean');
        expect($casts)->toHaveKey('available', 'boolean');
    });

    it('can be marked as worked', function () {
        $node = TronNode::create([
            'name' => 'test-node',
            'full_node' => ['url' => 'https://api.trongrid.io'],
            'solidity_node' => ['url' => 'https://api.trongrid.io'],
            'block_number' => 0,
            'worked' => true,
        ]);

        expect($node->worked)->toBeTrue();
    });

    it('can be marked as available', function () {
        $node = TronNode::create([
            'name' => 'test-node',
            'full_node' => ['url' => 'https://api.trongrid.io'],
            'solidity_node' => ['url' => 'https://api.trongrid.io'],
            'block_number' => 0,
            'available' => true,
        ]);

        expect($node->available)->toBeTrue();
    });

    it('has many wallets', function () {
        $node = new TronNode();

        expect($node->wallets())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
    });

    it('can create an API instance', function () {
        $node = new TronNode([
            'full_node' => ['url' => 'https://api.trongrid.io'],
            'solidity_node' => ['url' => 'https://api.trongrid.io'],
        ]);

        $api = $node->api();

        expect($api)->toBeInstanceOf(Api::class);
    });
});

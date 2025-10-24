<?php

use sakoora0x\LaravelTronModule\Api\TRC20Contract;
use sakoora0x\LaravelTronModule\Api\ApiManager;

describe('TRC20Contract (Simple)', function () {
    beforeEach(function () {
        $this->manager = Mockery::mock(ApiManager::class);
        $this->contractAddress = 'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t'; // USDT on Tron
    });

    it('can be instantiated', function () {
        $contract = new TRC20Contract($this->manager, $this->contractAddress);

        expect($contract)->toBeInstanceOf(TRC20Contract::class);
        expect($contract->address)->toBe($this->contractAddress);
    });

    it('throws exception when function not found in ABI', function () {
        $contract = new TRC20Contract($this->manager, $this->contractAddress);

        expect(fn() => $contract->triggerConstantContract('nonExistentFunction'))
            ->toThrow(Exception::class, 'Function nonExistentFunction not found in ABI');
    });
});

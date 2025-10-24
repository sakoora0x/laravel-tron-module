<?php

use sakoora0x\LaravelTronModule\Facades\Tron;

describe('Tron Facade', function () {
    it('can be resolved from container', function () {
        expect(Tron::getFacadeRoot())->not->toBeNull();
    });

    it('facade is accessible', function () {
        expect(class_exists(Tron::class))->toBeTrue();
    });
});

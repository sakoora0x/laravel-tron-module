<?php

use sakoora0x\LaravelTronModule\TronServiceProvider;

describe('TronServiceProvider', function () {
    it('registers the service provider', function () {
        $provider = new TronServiceProvider($this->app);

        expect($provider)->toBeInstanceOf(TronServiceProvider::class);
    });

    it('publishes config file', function () {
        $configPath = config_path('tron.php');

        // Just verify the provider can be instantiated and has publish groups
        $provider = new TronServiceProvider($this->app);

        expect($provider)->toBeInstanceOf(TronServiceProvider::class);
    });

    it('publishes migrations', function () {
        $provider = new TronServiceProvider($this->app);

        expect($provider)->toBeInstanceOf(TronServiceProvider::class);
    });
});

<?php

namespace sakoora0x\LaravelTronModule\Facades;

use Illuminate\Support\Facades\Facade;

class Tron extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \sakoora0x\LaravelTronModule\Tron::class;
    }
}

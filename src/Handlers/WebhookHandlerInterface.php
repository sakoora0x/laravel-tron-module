<?php

namespace sakoora0x\LaravelTronModule\Handlers;

use sakoora0x\LaravelTronModule\Models\TronAddress;
use sakoora0x\LaravelTronModule\Models\TronDeposit;
use sakoora0x\LaravelTronModule\Models\TronTransaction;

interface WebhookHandlerInterface
{
    public function handle(TronDeposit $deposit): void;
}

<?php

namespace sakoora0x\LaravelTronModule\Handlers;

use Illuminate\Support\Facades\Log;
use sakoora0x\LaravelTronModule\Models\TronDeposit;

class EmptyWebhookHandler implements WebhookHandlerInterface
{
    public function handle(TronDeposit $deposit): void
    {
        Log::error('NEW DEPOSIT FOR ADDRESS '.$deposit->address->address.' = '.$deposit->txid);
    }
}

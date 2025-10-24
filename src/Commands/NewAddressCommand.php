<?php

namespace sakoora0x\LaravelTronModule\Commands;

use Illuminate\Console\Command;
use sakoora0x\LaravelTronModule\Facades\Tron;
use sakoora0x\LaravelTronModule\Models\TronWallet;

class NewAddressCommand extends Command
{
    protected $signature = 'tron:new-address';

    protected $description = 'Generate Address for Tron Wallet';

    public function handle(): void
    {
        $this->info('You are about to generate address for Tron Wallet');

        $wallets = TronWallet::get();
        if ($wallets->count() === 0) {
            $this->alert("The list of wallets is empty, first create a wallet.");
            return;
        }

        $walletName = $this->choice('Choice wallet', $wallets->map(fn(TronWallet $wallet) => $wallet->name)->all());

        /** @var TronWallet $wallet */
        $wallet = $wallets->firstWhere('name', $walletName);

        $address = Tron::createAddress($wallet);
        $address->save();

        $this->info('Address '.$address->address.' with index '.$address->index.' successfully generated!');
    }
}

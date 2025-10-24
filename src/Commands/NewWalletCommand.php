<?php

namespace sakoora0x\LaravelTronModule\Commands;

use Illuminate\Console\Command;
use sakoora0x\LaravelTronModule\Facades\Tron;
use sakoora0x\LaravelTronModule\Models\TronNode;
use sakoora0x\LaravelTronModule\Models\TronWallet;

class NewWalletCommand extends Command
{
    protected $signature = 'tron:new-wallet';

    protected $description = 'Create a new TronWallet';

    public function handle(): void
    {
        $this->info('You are about to create a new Tron Wallet');

        do {
            $error = false;
            $name = $this->ask('Please, enter unique wallet name');
            if (empty($name)) {
                $error = true;
                $this->error('Wallet name is required!');
            } else {
                if (TronWallet::whereName($name)->count() > 0) {
                    $error = true;
                    $this->error('Name is busy!');
                }
            }
        } while ($error);

        do {
            $error = false;
            $mnemonic = $this->ask('Please, enter mnemonic phrase (optional)');
            if (!empty($mnemonic) && !Tron::mnemonicValidate($mnemonic)) {
                $error = true;
                $this->error('Mnemonic Phrase is not valid!');
            }
        } while ($error);

        if (empty($mnemonic)) {
            $mnemonic = implode(' ', Tron::mnemonicGenerate());
        }

        $wallet = Tron::createWallet($name, $mnemonic);
        $wallet->save();

        $this->info('Tron Wallet #'.$wallet->id.' successfully created!');
        $this->info('Mnemonic: '.$mnemonic);
    }
}

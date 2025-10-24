<?php

namespace sakoora0x\LaravelTronModule\Concerns;

use BIP\BIP44;
use sakoora0x\LaravelTronModule\Api\Helpers\AddressHelper;
use sakoora0x\LaravelTronModule\Enums\TronModel;
use sakoora0x\LaravelTronModule\Facades\Tron;
use sakoora0x\LaravelTronModule\Models\TronAddress;
use sakoora0x\LaravelTronModule\Models\TronNode;
use sakoora0x\LaravelTronModule\Models\TronWallet;
use sakoora0x\LaravelTronModule\Support\Key;

trait Address
{
    public function createAddress(TronWallet $wallet, ?string $title = null, ?int $index = null, ?string $seed = null): TronAddress
    {
        $address = $this->newAddress($wallet, $title, $index, $seed);
        $address->save();

        return $address;
    }

    public function newAddress(TronWallet $wallet, ?string $title = null, ?int $index = null, ?string $seed = null): TronAddress
    {
        if ($index === null) {
            $index = $wallet->addresses()->max('index');
            $index = $index === null ? 0 : ($index + 1);
        }

        if( !$seed ) {
            $seed = $wallet->seed;
        }

        if( !$seed ) {
            throw new \Exception('Argument Seed is required.');
        }

        $hdKey = BIP44::fromMasterSeed($seed)
            ->derive("m/44'/195'/0'/0")
            ->deriveChild($index);
        $privateKey = (string)$hdKey->privateKey;

        $addressString = AddressHelper::toBase58('41'.Key::privateKeyToAddress($privateKey));

        /** @var class-string<TronAddress> $addressModel */
        $addressModel = Tron::getModel(TronModel::Address);

        $address = new $addressModel([
            'address' => $addressString,
            'title' => $title,
            'index' => $index,
        ]);
        $address->wallet()->associate($wallet);
        $address->private_key = $privateKey;

        return $address;
    }

    public function importAddress(TronWallet $wallet, string $address)
    {
        return $wallet->addresses()->create([
            'address' => $address,
            'watch_only' => true,
        ]);
    }

    public function validateAddress(string $address, ?TronNode $node = null): bool
    {
        if( !$node ) {
            $node = Tron::getNode();
        }
        $node->increment('requests', 1);

        return $node->api()->validateAddress($address);
    }
}

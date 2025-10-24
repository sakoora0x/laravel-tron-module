<?php

namespace sakoora0x\LaravelTronModule;

use Illuminate\Database\Eloquent\Model;
use sakoora0x\LaravelTronModule\Api\Api;
use sakoora0x\LaravelTronModule\Concerns\Address;
use sakoora0x\LaravelTronModule\Concerns\Mnemonic;
use sakoora0x\LaravelTronModule\Concerns\Node;
use sakoora0x\LaravelTronModule\Concerns\Transfer;
use sakoora0x\LaravelTronModule\Concerns\TRC20;
use sakoora0x\LaravelTronModule\Concerns\Wallet;
use sakoora0x\LaravelTronModule\Enums\TronModel;
use sakoora0x\LaravelTronModule\Models\TronNode;

class Tron
{
    use Node, Mnemonic, Wallet, Address, TRC20, Transfer;

    /**
     * @param TronModel $model
     * @return class-string<Model>
     */
    public function getModel(TronModel $model): string
    {
        return config('tron.models.'.$model->value);
    }

    /**
     * @return class-string<Api>
     */
    public function getApi(): string
    {
        return config('tron.models.api');
    }

    public function getNode(): TronNode
    {
        /** @var TronNode $node */
        $node = $this->getModel(TronModel::Node)::query()
            ->where('worked', '=', true)
            ->where('available', '=', true)
            ->orderBy('requests')
            ->firstOrFail();

        return $node;
    }
}

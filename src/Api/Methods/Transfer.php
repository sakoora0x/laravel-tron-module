<?php

namespace sakoora0x\LaravelTronModule\Api\Methods;

use Brick\Math\BigDecimal;
use sakoora0x\LaravelTronModule\Api\Api;
use sakoora0x\LaravelTronModule\Api\DTO\TransferPreviewDTO;
use sakoora0x\LaravelTronModule\Api\DTO\TransferSendDTO;
use sakoora0x\LaravelTronModule\Api\Helpers\AddressHelper;
use sakoora0x\LaravelTronModule\Api\Helpers\AmountHelper;

class Transfer
{
    protected ?TransferPreviewDTO $preview = null;

    public function __construct(
        protected readonly Api  $api,
        public readonly string  $from,
        public readonly string  $to,
        public readonly BigDecimal $amount,
    )
    {
    }

    public function preview(
        BigDecimal|float|int|string|null $balance = null,
        ?int $bandwidth = null
    ): TransferPreviewDTO
    {
        if( $this->preview !== null ) {
            return $this->preview;
        }

        $error = null;
        $from = null;
        $fromResources = null;
        $to = null;
        $balanceBefore = $balance !== null ? BigDecimal::of($balance) : null;
        $balanceAfter = null;
        $activateFee = null;
        $transaction = null;
        $bandwidthRequired = null;
        $bandwidthBefore = $bandwidth;
        $bandwidthAfter = null;
        $bandwidthFee = null;

        try {
            $from = $this->api->getAccount($this->from);
            $fromResources = $this->api->getAccountResources($this->from);
            $to = $this->api->getAccount($this->to);

            if (!$from->activated) {
                throw new \Exception('From Address is not activated');
            }

            if( $balanceBefore === null ) {
                $balanceBefore = $from->balance;
            }
            $balanceAfter = $balanceBefore->minus($this->amount);

            if (!$to->activated) {
                $activateFee = AmountHelper::sunToDecimal(100000);
                $balanceAfter = $balanceAfter->minus($activateFee);
            }
            if ($balanceAfter->isNegative()) {
                throw new \Exception('Insufficient balance');
            }

            $transaction = $this->api->manager->request('wallet/createtransaction', null, [
                'owner_address' => AddressHelper::toHex($this->from),
                'to_address' => AddressHelper::toHex($this->to),
                'amount' => AmountHelper::decimalToSun($this->amount),
            ]);

            $bandwidthRequired = $to->activated ? strlen($transaction['raw_data_hex']) + 1 : 0;
            if( $bandwidthBefore === null ) {
                $bandwidthBefore = $fromResources->bandwidthAvailable;
            }
            if( $bandwidthRequired > $bandwidthBefore ) {
                $bandwidthFee = AmountHelper::sunToDecimal(($bandwidthRequired + 1) * 1000);
                $balanceAfter = $balanceAfter->minus($bandwidthFee);
                $bandwidthAfter = 0;
            }
            else {
                $bandwidthFee = BigDecimal::of(0);
                $bandwidthAfter = $bandwidthBefore - $bandwidthRequired;
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        $this->preview = new TransferPreviewDTO(
            error: $error,
            from: $from,
            fromResources: $fromResources,
            to: $to,
            balanceBefore: $balanceBefore,
            balanceAfter: $balanceAfter,
            activateFee: $activateFee,
            transaction: $transaction,
            bandwidthRequired: $bandwidthRequired,
            bandwidthBefore: $bandwidthBefore,
            bandwidthAfter: $bandwidthAfter,
            bandwidthFee: $bandwidthFee
        );

        return $this->preview;
    }

    public function send(string $privateKey): TransferSendDTO
    {
        $preview = $this->preview();
        if( $preview->hasError() ) {
            throw new \Exception($preview->error);
        }

        $transaction = $this->api->signTransaction($preview->transaction, $privateKey);

        $data = $this->api->manager->request('wallet/broadcasttransaction', null, $transaction);
        if (!isset($data['txid'])) {
            throw new \Exception($response['Error'] ?? print_r($data, true));
        }

        return new TransferSendDTO(
            txid: $data['txid'],
            preview: $preview,
        );
    }
}

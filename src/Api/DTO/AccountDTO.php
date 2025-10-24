<?php

namespace sakoora0x\LaravelTronModule\Api\DTO;

use Brick\Math\BigDecimal;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use sakoora0x\LaravelTronModule\Api\Helpers\AmountHelper;

class AccountDTO
{
    public function __construct(
        public readonly string   $address,
        public readonly array    $data,
        public readonly bool     $activated,
        public readonly ?BigDecimal $balance,
        public readonly ?Carbon  $createTime,
        public readonly ?Carbon  $lastOperationTime,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'address' => $this->address,
            'activated' => $this->activated,
            'balance' => $this->balance?->__toString(),
            'createTime' => $this->createTime?->toDateTimeString(),
            'lastOperationTime' => $this->lastOperationTime?->toDateTimeString(),
        ];
    }

    public static function fromArray(string $address, array $data): static
    {
        $activated = isset($data['create_time']);
        $balance = $activated ? AmountHelper::sunToDecimal($data['balance'] ?? 0) : null;
        $createTime = $activated ? Date::createFromTimestampMs($data['create_time']) : null;
        $lastOperationTime = $activated && isset($data['latest_opration_time']) ? Date::createFromTimestampMs($data['latest_opration_time']) : null;

        return new static(
            address: $address,
            data: $data,
            activated: $activated,
            balance: $balance,
            createTime: $createTime,
            lastOperationTime: $lastOperationTime,
        );
    }
}

<?php

namespace sakoora0x\LaravelTronModule\Models;

use Illuminate\Database\Eloquent\Model;
use sakoora0x\LaravelTronModule\Api\TRC20Contract;
use sakoora0x\LaravelTronModule\Facades\Tron;

class TronTRC20 extends Model
{
    public $timestamps = false;

    protected $table = 'tron_trc20';

    protected $fillable = [
        'address',
        'name',
        'symbol',
        'decimals',
    ];

    protected $casts = [
        'decimals' => 'integer',
    ];
}

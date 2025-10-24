<?php

namespace sakoora0x\LaravelTronModule\Enums;

enum TronTransactionType: string
{
    case INCOMING = 'in';
    case OUTGOING = 'out';
}

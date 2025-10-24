<?php

use Brick\Math\BigDecimal;
use sakoora0x\LaravelTronModule\Api\Helpers\AmountHelper;

describe('AmountHelper', function () {
    describe('toDecimal', function () {
        it('converts value to BigDecimal', function () {
            $result = AmountHelper::toDecimal(1000000, 6);

            expect($result)->toBeInstanceOf(BigDecimal::class);
            expect($result->toFloat())->toBe(1.0);
        });

        it('handles string values', function () {
            $result = AmountHelper::toDecimal('1000000', 6);

            expect($result)->toBeInstanceOf(BigDecimal::class);
            expect($result->toFloat())->toBe(1.0);
        });

        it('handles float values', function () {
            $result = AmountHelper::toDecimal(1.5, 0);

            expect($result)->toBeInstanceOf(BigDecimal::class);
        });

        it('handles BigDecimal input', function () {
            $input = BigDecimal::of('1000000');
            $result = AmountHelper::toDecimal($input, 6);

            expect($result)->toBeInstanceOf(BigDecimal::class);
            expect($result->toFloat())->toBe(1.0);
        });

        it('returns value unchanged when decimals is 0', function () {
            $result = AmountHelper::toDecimal(100, 0);

            expect($result->toInt())->toBe(100);
        });
    });

    describe('sunToDecimal', function () {
        it('converts sun to TRX with 6 decimals', function () {
            $result = AmountHelper::sunToDecimal(1000000);

            expect($result)->toBeInstanceOf(BigDecimal::class);
            expect($result->toFloat())->toBe(1.0);
        });

        it('handles partial sun amounts', function () {
            $result = AmountHelper::sunToDecimal(500000);

            expect($result->toFloat())->toBe(0.5);
        });

        it('handles zero', function () {
            $result = AmountHelper::sunToDecimal(0);

            expect($result->toInt())->toBe(0);
        });
    });

    describe('toSun', function () {
        it('converts decimal to sun units', function () {
            $result = AmountHelper::toSun(1.0, 6);

            expect($result)->toBe(1000000);
        });

        it('handles string values', function () {
            $result = AmountHelper::toSun('2.5', 6);

            expect($result)->toBe(2500000);
        });

        it('handles fractional values', function () {
            $result = AmountHelper::toSun(0.123456, 6);

            expect($result)->toBe(123456);
        });

        it('rounds properly', function () {
            $result = AmountHelper::toSun(1.5555555, 6);

            expect($result)->toBeInt();
        });
    });

    describe('decimalToSun', function () {
        it('converts TRX to sun', function () {
            $result = AmountHelper::decimalToSun(1.0);

            expect($result)->toBe(1000000);
        });

        it('handles large values', function () {
            $result = AmountHelper::decimalToSun(1000);

            expect($result)->toBe(1000000000);
        });

        it('handles small values', function () {
            $result = AmountHelper::decimalToSun(0.000001);

            expect($result)->toBe(1);
        });
    });
});

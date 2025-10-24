<?php

use Brick\Math\BigDecimal;
use Illuminate\Database\Eloquent\Model;
use sakoora0x\LaravelTronModule\Casts\BigDecimalCast;

describe('BigDecimalCast', function () {
    beforeEach(function () {
        $this->cast = new BigDecimalCast();
        $this->model = new class extends Model {
            protected $fillable = ['amount'];
        };
    });

    describe('get', function () {
        it('converts string value to BigDecimal', function () {
            $result = $this->cast->get($this->model, 'amount', '100.50', []);

            expect($result)->toBeInstanceOf(BigDecimal::class);
            expect($result->toFloat())->toBe(100.5);
        });

        it('handles null or zero values', function () {
            $result = $this->cast->get($this->model, 'amount', null, []);

            expect($result)->toBeInstanceOf(BigDecimal::class);
            expect($result->toInt())->toBe(0);
        });

        it('handles integer values', function () {
            $result = $this->cast->get($this->model, 'amount', 1000, []);

            expect($result)->toBeInstanceOf(BigDecimal::class);
            expect($result->toInt())->toBe(1000);
        });

        it('handles large numbers', function () {
            $result = $this->cast->get($this->model, 'amount', '999999999999999999', []);

            expect($result)->toBeInstanceOf(BigDecimal::class);
            expect($result->__toString())->toBe('999999999999999999');
        });
    });

    describe('set', function () {
        it('converts BigDecimal to string', function () {
            $decimal = BigDecimal::of('100.50');
            $result = $this->cast->set($this->model, 'amount', $decimal, []);

            // BigDecimal toString() preserves trailing zeros for decimals
            expect($result)->toMatch('/^100\.5(0)?$/');
        });

        it('returns non-BigDecimal values unchanged', function () {
            $result = $this->cast->set($this->model, 'amount', '200', []);

            expect($result)->toBe('200');
        });

        it('handles null values', function () {
            $result = $this->cast->set($this->model, 'amount', null, []);

            expect($result)->toBeNull();
        });
    });
});

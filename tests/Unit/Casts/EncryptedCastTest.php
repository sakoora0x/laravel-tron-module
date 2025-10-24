<?php

use Illuminate\Database\Eloquent\Model;
use sakoora0x\LaravelTronModule\Casts\EncryptedCast;

describe('EncryptedCast', function () {
    beforeEach(function () {
        $this->cast = new EncryptedCast();

        // Create a test model with password support
        $this->model = new class extends Model {
            protected $fillable = ['password', 'private_key'];
            public $plain_password = 'test-password';
            public $password = 'test-password';
        };
    });

    describe('set', function () {
        it('encrypts non-null values', function () {
            $encrypted = $this->cast->set($this->model, 'private_key', 'secret-key', []);

            expect($encrypted)->not->toBe('secret-key');
            expect($encrypted)->toBeString();
        });

        it('returns null for null values', function () {
            $result = $this->cast->set($this->model, 'private_key', null, []);

            expect($result)->toBeNull();
        });

        it('returns null for empty string', function () {
            $result = $this->cast->set($this->model, 'private_key', '', []);

            expect($result)->toBeNull();
        });
    });

    describe('get', function () {
        it('returns null for null values', function () {
            $result = $this->cast->get($this->model, 'private_key', null, []);

            expect($result)->toBeNull();
        });

        it('decrypts encrypted values', function () {
            // First encrypt a value
            $encrypted = $this->cast->set($this->model, 'private_key', 'secret-key', []);

            // Then decrypt it
            $decrypted = $this->cast->get($this->model, 'private_key', $encrypted, []);

            expect($decrypted)->toBe('secret-key');
        });

        it('handles round-trip encryption/decryption', function () {
            $originalValue = 'my-super-secret-private-key-12345';

            $encrypted = $this->cast->set($this->model, 'private_key', $originalValue, []);
            $decrypted = $this->cast->get($this->model, 'private_key', $encrypted, []);

            expect($decrypted)->toBe($originalValue);
        });
    });
});

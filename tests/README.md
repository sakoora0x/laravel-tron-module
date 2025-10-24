# Laravel Tron Module Tests

This directory contains the test suite for the Laravel Tron Module package using [Pest PHP](https://pestphp.com/).

## Installation

First, install the testing dependencies:

```bash
composer install
```

## Running Tests

### Run all tests
```bash
composer test
```

or

```bash
vendor/bin/pest
```

### Run tests with coverage
```bash
composer test-coverage
```

### Run specific test files
```bash
vendor/bin/pest tests/Unit/Support/KeyTest.php
```

### Run tests with filters
```bash
vendor/bin/pest --filter="can create a wallet"
```

## Test Structure

The test suite is organized into the following directories:

### Unit Tests (`tests/Unit/`)

Unit tests focus on testing individual components in isolation:

- **`Support/`** - Tests for utility classes
  - `KeyTest.php` - Tests for cryptographic key operations (private/public key conversion, address generation)
  - `UtilsTest.php` - Tests for utility functions (hex conversion, address validation, hashing, etc.)

- **`Api/Helpers/`** - Tests for API helper classes
  - `AddressHelperTest.php` - Tests for Tron address format conversion (Base58 ↔ Hex)
  - `AmountHelperTest.php` - Tests for amount conversion utilities (SUN ↔ TRX, decimal handling)

- **`Api/`** - Tests for API client components
  - `TRC20ContractTest.php` - Tests for TRC20 token contract interactions

- **`Models/`** - Tests for Eloquent models
  - `TronWalletTest.php` - Tests for wallet model (relationships, attributes, password handling)
  - `TronAddressTest.php` - Tests for address model (fillable attributes, casts, relationships)
  - `TronTransactionTest.php` - Tests for transaction model (types, relationships)
  - `TronNodeTest.php` - Tests for node model (API instantiation, relationships)

- **`Enums/`** - Tests for enum classes
  - `TronTransactionTypeTest.php` - Tests for transaction type enum (INCOMING/OUTGOING)

### Feature Tests (`tests/Feature/`)

Feature tests verify the integration of multiple components:

- `TronFacadeTest.php` - Tests for the Tron facade accessibility
- `ServiceProviderTest.php` - Tests for the service provider registration
- `WalletManagementTest.php` - End-to-end tests for wallet and address management

## Key Test Coverage Areas

### 1. Cryptographic Operations
- Private key to public key conversion
- Public key to address generation
- Key format validation

### 2. Utility Functions
- Hex/binary conversions
- Address validation and checksums
- SHA3/Keccak256 hashing
- BigNumber operations
- Amount formatting and conversion

### 3. Address Management
- Base58 to Hex conversion
- Hex to Base58 conversion
- Address format validation

### 4. Amount Conversions
- SUN to TRX decimal conversion
- TRX to SUN conversion
- Custom token decimal handling
- BigDecimal support

### 5. Models and Relationships
- Wallet creation and management
- Address generation and tracking
- Transaction recording
- Node configuration
- TRC20 token support

### 6. Security Features
- Password encryption/decryption
- Private key encryption
- Mnemonic phrase handling
- Seed encryption

## Writing New Tests

### Basic Test Structure

Pest uses a describe/it syntax:

```php
<?php

use YourNamespace\YourClass;

describe('YourClass', function () {
    it('does something', function () {
        $result = YourClass::doSomething();

        expect($result)->toBe('expected value');
    });

    it('throws exception for invalid input', function () {
        expect(fn() => YourClass::doSomething('invalid'))
            ->toThrow(InvalidArgumentException::class);
    });
});
```

### Using beforeEach/afterEach

```php
describe('Feature', function () {
    beforeEach(function () {
        // Setup code runs before each test
        $this->model = Model::create(['name' => 'test']);
    });

    afterEach(function () {
        // Cleanup code runs after each test
    });

    it('tests something', function () {
        expect($this->model->name)->toBe('test');
    });
});
```

### Mocking Dependencies

```php
use Mockery;

it('mocks external dependencies', function () {
    $mock = Mockery::mock(ExternalService::class);
    $mock->shouldReceive('fetch')
        ->once()
        ->andReturn('mocked response');

    $result = new MyClass($mock);

    expect($result->getData())->toBe('mocked response');
});
```

## Best Practices

1. **Test Naming**: Use descriptive test names that explain what is being tested
2. **Arrange-Act-Assert**: Structure tests clearly with setup, execution, and assertion phases
3. **One Assertion Per Test**: Keep tests focused on a single behavior
4. **Mock External Dependencies**: Don't make real API calls in unit tests
5. **Use Type Hints**: Leverage PHP's type system for better test clarity
6. **Test Edge Cases**: Include tests for boundary conditions and error cases

## Continuous Integration

These tests are designed to run in CI/CD pipelines. Ensure your CI configuration includes:

```yaml
- composer install
- vendor/bin/pest --coverage --min=80
```

## Troubleshooting

### Missing Extensions

If you encounter errors about missing PHP extensions:

```bash
# Install required extensions
sudo apt-get install php-gmp php-ctype
# or on macOS
brew install php
```

### Database Issues

The tests use an in-memory SQLite database by default. If you need to use a different database:

1. Update `phpunit.xml` with your database configuration
2. Ensure migrations are properly loaded in `TestCase.php`

### Mocking Issues

If Mockery assertions fail, ensure you have:

```php
afterEach(function () {
    Mockery::close();
});
```

## Contributing

When contributing new features:

1. Write tests first (TDD approach)
2. Ensure all tests pass: `vendor/bin/pest`
3. Check code coverage: `vendor/bin/pest --coverage`
4. Follow existing test patterns and naming conventions

## Resources

- [Pest Documentation](https://pestphp.com/docs)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Mockery Documentation](http://docs.mockery.io/)
- [Laravel Testing](https://laravel.com/docs/testing)

<?php

namespace sakoora0x\LaravelTronModule\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use sakoora0x\LaravelTronModule\TronServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            TronServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Load migrations if they exist
        $migrations = [
            'create_tron_nodes_table.php.stub',
            'create_tron_wallets_table.php.stub',
            'create_tron_addresses_table.php.stub',
            'create_tron_transactions_table.php.stub',
            'create_tron_deposits_table.php.stub',
        ];

        foreach ($migrations as $migrationFile) {
            $migrationPath = __DIR__.'/../database/migrations/'.$migrationFile;
            if (file_exists($migrationPath)) {
                $migration = include $migrationPath;
                $migration->up();
            }
        }

        // Set default config values
        config()->set('tron.models.wallet', \sakoora0x\LaravelTronModule\Models\TronWallet::class);
        config()->set('tron.models.address', \sakoora0x\LaravelTronModule\Models\TronAddress::class);
        config()->set('tron.models.transaction', \sakoora0x\LaravelTronModule\Models\TronTransaction::class);
        config()->set('tron.models.deposit', \sakoora0x\LaravelTronModule\Models\TronDeposit::class);
        config()->set('tron.models.node', \sakoora0x\LaravelTronModule\Models\TronNode::class);
        config()->set('tron.models.trc20', \sakoora0x\LaravelTronModule\Models\TronTRC20::class);
        config()->set('tron.models.api', \sakoora0x\LaravelTronModule\Api\Api::class);
    }
}

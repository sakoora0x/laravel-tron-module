<?php

namespace sakoora0x\LaravelTronModule;

use sakoora0x\LaravelTronModule\Commands\NewTRC20Command;
use sakoora0x\LaravelTronModule\Commands\NewWalletCommand;
use sakoora0x\LaravelTronModule\Commands\NewAddressCommand;
use sakoora0x\LaravelTronModule\Commands\ImportAddressCommand;
use sakoora0x\LaravelTronModule\Commands\AddressSyncCommand;
use sakoora0x\LaravelTronModule\Commands\NewNodeCommand;
use sakoora0x\LaravelTronModule\Commands\NodeSyncCommand;
use sakoora0x\LaravelTronModule\Commands\SyncCommand;
use sakoora0x\LaravelTronModule\Commands\WalletSyncCommand;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TronServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('tron')
            ->hasConfigFile()
            ->hasMigrations([
                'create_tron_nodes_table',
                'create_tron_wallets_table',
                'create_tron_trc20_table',
                'create_tron_addresses_table',
                'create_tron_transactions_table',
                'create_tron_deposits_table',
            ])
            ->runsMigrations()
            ->hasCommands(
                NewNodeCommand::class,
                NewWalletCommand::class,
                NewAddressCommand::class,
                ImportAddressCommand::class,
                NewTRC20Command::class,
                SyncCommand::class,
                AddressSyncCommand::class,
                WalletSyncCommand::class,
                NodeSyncCommand::class,
            )
            ->hasInstallCommand(function(InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations();
            });

        $this->app->singleton(Tron::class);
    }
}

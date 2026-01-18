<?php
namespace App\Providers;

use App\Application\BankAccount\Contracts\BankAccountCrudRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\BankAccount\EloquentBankAccountRepository;
use Illuminate\Support\ServiceProvider;


class BankAccountProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(BankAccountCrudRepository::class, EloquentBankAccountRepository::class);
    }

    public function boot()
    {
        //
    }
}

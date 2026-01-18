<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthProvider::class,
    App\Providers\DepartmentProvider::class,
    App\Providers\BankAccountProvider::class,
    App\Providers\TaxProvider::class,
    App\Providers\FamilyProvider::class,
    App\Providers\WarehouseProvider::class,
    Infrastructure\Laravel\Providers\CategoryServiceProvider::class,
    Infrastructure\Laravel\Providers\ProductServiceProvider::class,


];

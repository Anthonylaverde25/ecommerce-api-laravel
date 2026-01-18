<?php
namespace App\Application\BankAccount\Contracts;

use App\Domain\BankAccount\Entities\BankAccount;

interface BankAccountCrudRepository
{
    public function index(): array;
    public function show(int $id): BankAccount;
    public function create(BankAccount $data): BankAccount;
    public function update(int $id, BankAccount $data): BankAccount;
}

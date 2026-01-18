<?php
namespace App\Application\Tax\Contracts;

interface TaxTypeCrudRepository
{
    public function index(): array;
}
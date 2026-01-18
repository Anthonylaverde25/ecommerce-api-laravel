<?php
namespace App\Application\Tax\Contracts;

use App\Domain\Tax\Entities\Tax;

interface TaxCrudRepository
{
   /**
    * @return Tax[]
    */
   public function index(): array;
   public function create(Tax $tax): Tax;
   public function update(int $taxId, Tax $tax): Tax;
   public function show(int $taxId): Tax;
}

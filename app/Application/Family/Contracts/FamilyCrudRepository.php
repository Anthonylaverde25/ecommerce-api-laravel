<?php
namespace App\Application\Family\Contracts;

use App\Domain\Family\Entities\Family;

interface FamilyCrudRepository
{
    public function index(): array;
    public function create(Family $data): Family;
    public function update(int $familyId, Family $family): Family;
    public function show(int $familyId): Family;
}
<?php
namespace App\Presentation\API\BankAccount\Controllers;

use App\Application\BankAccount\Services\BankAccountService;
use App\Domain\BankAccount\DTOs\CreateBankAccountData;
use App\Domain\BankAccount\DTOs\UpdateBankAccountData;
use App\Presentation\API\BankAccount\Requests\CreateBankAccountRequest;
use App\Presentation\API\BankAccount\Requests\UpdateBankAccountRequest;
use App\Presentation\API\BankAccount\Resources\BankAccountCollection;
use App\Presentation\API\BankAccount\Resources\BankAccountDetailsResource;
use App\Presentation\API\BankAccount\Resources\BankAccountResource;
use Illuminate\Http\JsonResponse;

class BankAccountController
{
    public function __construct(
        private readonly BankAccountService $bankAccountService
    ) {
    }

    public function index()
    {
        $bankAccounts = $this->bankAccountService->index();

        return (new BankAccountCollection($bankAccounts))
            ->response()
            ->setStatusCode(200);
    }

    public function show(int $id)
    {
        $bankAccount = $this->bankAccountService->show($id);

        return (new BankAccountDetailsResource($bankAccount))
            ->response()
            ->setStatusCode(200);
    }

    public function store(CreateBankAccountRequest $request): JsonResponse
    {
        $data = CreateBankAccountData::fromArray($request->validated());
        $bankAccount = $this->bankAccountService->create($data);

        return (new BankAccountResource($bankAccount))
            ->response()
            ->setStatusCode(201);
    }

    public function update(int $id, UpdateBankAccountRequest $request): JsonResponse
    {
        $data = UpdateBankAccountData::fromArray($request->validated());
        $bankAccount = $this->bankAccountService->update($id, $data);

        return (new BankAccountResource($bankAccount))
            ->response()
            ->setStatusCode(200);
    }
}

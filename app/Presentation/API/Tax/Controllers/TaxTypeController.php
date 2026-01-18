<?php
declare(strict_types=1);
namespace App\Presentation\API\Tax\Controllers;

use App\Application\Tax\Services\TaxService;
use App\Http\Controllers\Controller;
use App\Presentation\API\Tax\Resources\TaxTypeCollection;
use Illuminate\Http\JsonResponse;

class TaxTypeController extends Controller
{
    public function __construct(
        private readonly TaxService $taxService
    ) {
    }

    public function index(): JsonResponse
    {
        $taxTypes = $this->taxService->indexTaxType();
        return (new TaxTypeCollection($taxTypes))
            ->response()
            ->setStatusCode(200);
    }
}
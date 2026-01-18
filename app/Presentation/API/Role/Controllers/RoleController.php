<?php
declare(strict_types=1);

namespace App\Presentation\API\Role\Controllers;

use App\Application\Role\Services\RoleService;
use App\Domain\Role\DTOs\CreateRoleData;
use App\Http\Controllers\Controller;
use App\Presentation\API\Role\Requests\CreateRoleRequest;
use App\Presentation\API\Role\Resources\RoleCollection;
use App\Presentation\API\Role\Resources\RoleResource;
use Illuminate\Http\JsonResponse;

/**
 * RoleController - Presentation Layer
 * 
 * Handles HTTP requests for Role operations
 */
class RoleController extends Controller
{
    public function __construct(
        private readonly RoleService $service
    ) {
    }

    /**
     * Display a listing of roles.
     */
    public function index(): JsonResponse
    {
        $roles = $this->service->index();
        return (new RoleCollection($roles))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Store a newly created role.
     */
    public function store(CreateRoleRequest $request): JsonResponse
    {
        $data = CreateRoleData::fromArray($request->validated());
        $role = $this->service->create($data);

        return (new RoleResource($role))
            ->response()
            ->setStatusCode(201);
    }
}

<?php
namespace App\Presentation\API\Auth\Controllers;

use App\Application\Auth\Services\UserService;
use App\Domain\Auth\DTOs\CreateUserData;
use App\Domain\Auth\DTOs\UpdateUserData;
use App\Http\Controllers\Controller;
use App\Presentation\API\Auth\Requests\UserCreateRequest;
use App\Presentation\API\Auth\Requests\UserUpdateRequest;
use App\Presentation\API\Auth\Resources\UserCollection;
use App\Presentation\API\Auth\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;


class UserController extends Controller
{
    public function __construct(
        private readonly UserService $service
    ) {
    }


    public function index(): JsonResponse
    {
        $users = $this->service->index();

        return (new UserCollection($users))
            ->response()
            ->setStatusCode(200);
    }

    public function show(int $id): JsonResponse
    {
        $user = $this->service->show($id);
        return (new UserResource($user))
            ->response()
            ->setStatusCode(200);
    }



    public function store(UserCreateRequest $request): JsonResponse
    {
        $data = CreateUserData::fromArray($request->validated());
        $user = $this->service->create($data);

        return (new UserResource($user))
            ->response()
            ->setStatusCode(201);


    }

    public function update(int $id, UserUpdateRequest $request): JsonResponse
    {
        $data = UpdateUserData::fromArray($request->validated());
        $user = $this->service->update($id, $data);
        return (new UserResource($user))
            ->response()
            ->setStatusCode(200);
    }


}
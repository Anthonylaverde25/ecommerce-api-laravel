<?php

namespace App\Presentation\API\Auth\Controllers;

use App\Application\Auth\UseCases\GetAuthenticatedUser\GetAuthenticatedUserUseCase;
use App\Application\Auth\UseCases\LoginUser\LoginUserDTO;
use App\Application\Auth\UseCases\LoginUser\LoginUserUseCase;
use App\Application\Auth\UseCases\LogoutAllDevices\LogoutAllDevicesUseCase;
use App\Application\Auth\UseCases\LogoutUser\LogoutUserUseCase;
use App\Application\Auth\UseCases\RegisterUser\RegisterUserDTO;
use App\Application\Auth\UseCases\RegisterUser\RegisterUserUseCase;
use App\Application\Auth\UseCases\RevokeToken\RevokeTokenUseCase;
use App\Application\Auth\Contracts\AuthRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Presentation\API\Auth\Requests\LoginRequest;
use App\Presentation\API\Auth\Requests\RegisterRequest;
use App\Presentation\API\Auth\Resources\UserResource;
use Illuminate\Http\Request;
use DomainException;

/**
 * AuthController - Presentation Layer
 * 
 * Controlador que maneja las peticiones HTTP relacionadas con autenticación.
 * Delega la lógica de negocio a los casos de uso (Application Layer).
 */
class AuthController extends Controller
{
    public function __construct(
        private readonly RegisterUserUseCase $registerUseCase,
        private readonly LoginUserUseCase $loginUseCase,
        private readonly LogoutUserUseCase $logoutUseCase,
        private readonly LogoutAllDevicesUseCase $logoutAllDevicesUseCase,
        private readonly GetAuthenticatedUserUseCase $getAuthenticatedUserUseCase,
        private readonly RevokeTokenUseCase $revokeTokenUseCase,
        private readonly AuthRepositoryInterface $authRepository
    ) {
    }

    /**
     * Register a new user.
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        try {
            $dto = new RegisterUserDTO(
                name: $request->name,
                email: $request->email,
                password: $request->password,
                deviceName: $request->device_name ?? 'web-browser'
            );

            $response = $this->registerUseCase->execute($dto);

            return response()->json($response->toArray(), 201);
        } catch (DomainException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Login a user.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        try {
            $dto = new LoginUserDTO(
                email: $request->email,
                password: $request->password,
                deviceName: $request->device_name ?? 'web-browser'
            );

            $response = $this->loginUseCase->execute($dto);

            return response()->json($response->toArray());
        } catch (DomainException $e) {
            return response()->json([
                'message' => 'Credenciales inválidas',
            ], 401);
        }
    }

    /**
     * Logout the current user (revoke current token).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $this->logoutUseCase->execute($request->user()->id);

        return response()->json([
            'message' => 'Logout exitoso',
        ]);
    }

    /**
     * Logout from all devices (revoke all tokens).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logoutAll(Request $request)
    {
        $this->logoutAllDevicesUseCase->execute($request->user()->id);

        return response()->json([
            'message' => 'Se cerraron todas las sesiones',
        ]);
    }

    /**
     * Get the authenticated user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        try {
            $response = $this->getAuthenticatedUserUseCase->execute($request->user()->id);

            return response()->json($response->toArray());
        } catch (DomainException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Get all active tokens for the authenticated user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function tokens(Request $request)
    {
        $tokens = $this->authRepository->getUserTokens($request->user()->id);

        return response()->json([
            'data' => $tokens,
        ]);
    }

    /**
     * Revoke a specific token by ID.
     *
     * @param Request $request
     * @param int $tokenId
     * @return \Illuminate\Http\JsonResponse
     */
    public function revokeToken(Request $request, $tokenId)
    {
        try {
            $this->revokeTokenUseCase->execute($request->user()->id, (int) $tokenId);

            return response()->json([
                'message' => 'Token revocado exitosamente',
            ]);
        } catch (DomainException $e) {
            return response()->json([
                'message' => 'Token no encontrado',
            ], 404);
        }
    }
}

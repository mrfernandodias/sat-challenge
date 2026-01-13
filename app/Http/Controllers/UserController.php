<?php

namespace App\Http\Controllers;

use App\Domain\User\DTOs\UserDTO;
use App\Domain\User\Services\UserService;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function index(): View
    {
        return view('users');
    }

    public function data(): JsonResponse
    {
        $users = $this->userService->getAllUsers();

        return response()->json([
            'data' => UserResource::collection($users),
        ]);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $dto = UserDTO::fromStoreRequest($request);
        $user = $this->userService->createUser($dto);

        return response()->json([
            'success' => true,
            'message' => 'Usuário criado com sucesso.',
            'user' => new UserResource($user),
        ], 201);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $dto = UserDTO::fromUpdateRequest($request);
        $user = $this->userService->updateUser($user, $dto);

        return response()->json([
            'success' => true,
            'message' => 'Usuário atualizado com sucesso.',
            'user' => new UserResource($user),
        ]);
    }

    public function destroy(User $user): JsonResponse
    {
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Você não pode excluir seu próprio usuário.',
            ], 403);
        }

        $this->userService->deleteUser($user);

        return response()->json([
            'success' => true,
            'message' => 'Usuário excluído com sucesso.',
        ]);
    }
}

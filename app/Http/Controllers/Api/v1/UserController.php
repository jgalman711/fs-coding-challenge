<?php

namespace App\Http\Controllers\Api\v1;

use App\Contracts\UserRepositoryInterface;
use App\Http\Resources\v1\UserResource;
use Illuminate\Http\JsonResponse;

class UserController extends BaseController
{
    protected $userRepositoryInterface;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->userRepositoryInterface = $userRepositoryInterface;        
    }

    public function index(): JsonResponse
    {
        $users = $this->userRepositoryInterface->findAll();
        if (empty($users)) {
            return $this->error('No users found');
        }
        return $this->success('Users retrieved successfully', UserResource::collection($users));
    }

    public function show(int $id): JsonResponse
    {
        $user = $this->userRepositoryInterface->findById($id);
        if (!$user) {
            return $this->error('No user found');
        }
        return $this->success('User retrieved successfully', new UserResource($user));
    }
}

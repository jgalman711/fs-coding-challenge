<?php

namespace App\Http\Controllers\Api\v1;

use App\Contracts\UserRepositoryInterface;
use App\Http\Resources\v1\UserResource;

class UserController extends BaseController
{
    protected $userRepositoryInterface;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->userRepositoryInterface = $userRepositoryInterface;        
    }

    public function index()
    {
        return $this->success(
            'Users retrieved successfully',
            UserResource::collection($this->userRepositoryInterface->findAll()),
        );
    }

    public function show(int $id)
    {
        return $this->success(
            'User retrieved successfully',
            new UserResource($this->userRepositoryInterface->findById($id)),
        );
    }
}

<?php

namespace App\Contracts;

use App\Entities\User;

interface UserRepositoryInterface
{
    public function createOrUpdateByEmail(array $userData): void;

    public function findAll(): array;

    public function findById(int $id): ?User;
}
<?php

namespace App\Services;

use App\Contracts\UserRepositoryInterface;

class UserImporterService
{
    protected $userFetcherService;

    protected $userRepositoryInterface;

    public function __construct(
        UserFetcherService $userFetcherService,
        UserRepositoryInterface $userRepositoryInterface,
    ) {
        $this->userFetcherService = $userFetcherService;
        $this->userRepositoryInterface = $userRepositoryInterface;
    }

    public function import(?int $results = null, ?string $nat = null): void
    {
        $usersData = $this->userFetcherService->fetch($results, $nat);

        foreach ($usersData as $userData) {
            $this->userRepositoryInterface->createOrUpdateByEmail($userData);
        }
    }
}

<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;

class UserImporterService
{
    protected $entityManagerInterface;

    protected $userFetcherService;

    public function __construct(
        EntityManagerInterface $entityManagerInterface,
        UserFetcherService $userFetcherService,
    ) {
        $this->entityManagerInterface = $entityManagerInterface;        
        $this->userFetcherService = $userFetcherService;
    }

    public function import(?int $results = null, ?string $nat = null): void
    {
        //
    }
}

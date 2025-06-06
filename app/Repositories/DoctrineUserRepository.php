<?php

namespace App\Repositories;

use App\Contracts\UserRepositoryInterface;
use App\Entities\User;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineUserRepository implements UserRepositoryInterface
{
    protected $entityManagerInterface;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;        
    }

    public function findAll(): array
    {
        return $this->entityManagerInterface->getRepository(User::class)->findAll();
    }

    public function findById(int $id): User
    {
        return $this->entityManagerInterface->getRepository(User::class)->find($id);
    }
}
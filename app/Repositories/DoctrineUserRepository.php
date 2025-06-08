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

    public function createOrUpdateByEmail(array $userData): User
    {
        $user = $this->entityManagerInterface
            ->getRepository(User::class)
            ->findOneBy(['email' => $userData['email']]);

        if (!$user) {
            $user = new User();
            $this->entityManagerInterface->persist($user);
        }

        $user->setName($userData['name']['first'] . ' ' . $userData['name']['last']);
        $user->setEmail($userData['email']);
        $user->setUsername($userData['login']['username']);
        $user->setPassword($userData['login']['password']);
        $user->setGender($userData['gender']);
        $user->setCountry($userData['location']['country']);
        $user->setCity($userData['location']['city']);
        $user->setPhone($userData['phone']);

        $this->entityManagerInterface->flush();

        return $user;
    }

    public function findAll(): array
    {
        return $this->entityManagerInterface->getRepository(User::class)->findAll();
    }

    public function findById(int $id): ?User
    {
        return $this->entityManagerInterface->getRepository(User::class)->find($id);
    }
}
<?php

namespace App\Services;

use App\Entities\User;
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
        $usersData = $this->userFetcherService->fetch($results, $nat);

        foreach ($usersData as $userData) {
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
        }
        $this->entityManagerInterface->flush();
    }
}

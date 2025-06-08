<?php

namespace Tests\Unit;

use App\Entities\User;
use App\Repositories\DoctrineUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    public function test_find_all_returns_array(): void
    {
        $user = new User();
        $mockEntityRepository = $this->createMock(EntityRepository::class);
        $mockEntityRepository->method('findAll')->willReturn([$user]);

        $mockEntityManager = $this->createMock(EntityManagerInterface::class);
        $mockEntityManager
            ->method('getRepository')
            ->with(User::class)
            ->willReturn($mockEntityRepository);

        $doctrineUserRepository = new DoctrineUserRepository($mockEntityManager);
        $result = $doctrineUserRepository->findAll();

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertInstanceOf(User::class, $result[0]);
    }

    public function test_find_user_array(): void
    {
        $user = new User();
        $mockEntityRepository = $this->createMock(EntityRepository::class);
        $mockEntityRepository->method('find')->willReturn($user);

        $mockEntityManager = $this->createMock(EntityManagerInterface::class);
        $mockEntityManager
            ->method('getRepository')
            ->with(User::class)
            ->willReturn($mockEntityRepository);

        $doctrineUserRepository = new DoctrineUserRepository($mockEntityManager);
        $result = $doctrineUserRepository->findById(1);
        $this->assertIsObject($result);
        $this->assertInstanceOf(User::class, $result);
    }

    public function test_find_all_returns_null_when_not_found(): void
    {
        $mockEntityRepository = $this->createMock(EntityRepository::class);
        $mockEntityRepository->method('findAll')->willReturn([]);

        $mockEntityManager = $this->createMock(EntityManagerInterface::class);
        $mockEntityManager
            ->method('getRepository')
            ->with(User::class)
            ->willReturn($mockEntityRepository);

        $doctrineUserRepository = new DoctrineUserRepository($mockEntityManager);
        $result = $doctrineUserRepository->findAll();

        $this->assertIsArray($result);
        $this->assertCount(0, $result);
    }

    public function test_find_user_returns_null_when_not_found(): void
    {
        $mockEntityRepository = $this->createMock(EntityRepository::class);
        $mockEntityRepository->method('find')->willReturn(null);

        $mockEntityManager = $this->createMock(EntityManagerInterface::class);
        $mockEntityManager
            ->method('getRepository')
            ->with(User::class)
            ->willReturn($mockEntityRepository);

        $doctrineUserRepository = new DoctrineUserRepository($mockEntityManager);
        $result = $doctrineUserRepository->findById(999);

        $this->assertNull($result);
    }

    public function test_create_or_update_new_user(): void
    {
        $userData = $this->fakeUserData();

        $mockEntityRepository = $this->createMock(EntityRepository::class);
        $mockEntityRepository
            ->method('findOneBy')
            ->with(['email' => $userData['email']])
            ->willReturn(null);

        $mockEntityManager = $this->createMock(EntityManagerInterface::class);
        $mockEntityManager
            ->method('getRepository')
            ->with(User::class)
            ->willReturn($mockEntityRepository);

        $mockEntityManager->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(User::class));

        $mockEntityManager->expects($this->once())
            ->method('flush');

        $doctrineUserRepository = new DoctrineUserRepository($mockEntityManager);
        $newUser = $doctrineUserRepository->createOrUpdateByEmail($userData);

        $this->assertIsObject($newUser);
        $this->assertInstanceOf(User::class, $newUser);
    }

    private function fakeUserData(): array
    {
        $faker = Factory::create();
        return [
            'name' => [
                'first' => $faker->firstName(),
                'last'  => $faker->lastName(),
            ],
            'email' => $faker->email(),
            'login' => [
                'username' => $faker->userName(),
                'password' => $faker->password(),
            ],
            'gender' => $faker->randomElement([
                'male',
                'female'
            ]),
            'location' => [
                'country' => 'Australia',
                'city'    => $faker->city(),
            ],
            'phone' => $faker->phoneNumber(),
            'nat'   => 'AU',
        ];
    }
}

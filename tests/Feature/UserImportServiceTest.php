<?php

namespace Tests\Feature;

use App\Repositories\DoctrineUserRepository;
use App\Services\UserFetcherService;
use App\Services\UserImporterService;
use Faker\Factory;
use Tests\TestCase;

class UserImportServiceTest extends TestCase
{
    public function test_imports_users_successfully(): void
    {
        $fakeUsers = $this->fakeUsers(100);

        $mockUserFetcherService = $this->createMock(UserFetcherService::class);
        $mockUserFetcherService->expects($this->once())
            ->method('fetch')
            ->willReturn($fakeUsers);
        $this->assertCount(100, $fakeUsers);

        $mockUserRepository = $this->createMock(DoctrineUserRepository::class);
        $mockUserRepository->expects($this->exactly(100))
            ->method('createOrUpdateByEmail');

        $userImporterService = new UserImporterService($mockUserFetcherService, $mockUserRepository);
        $userImporterService->import();
    }

    private function fakeUsers(int $count): array
    {
        $faker = Factory::create();

        return array_map(function () use ($faker) {
            return [
                'name' => [
                    'first' => $faker->firstName,
                    'last'  => $faker->lastName,
                ],
                'email' => $faker->unique()->safeEmail,
                'login' => [
                    'username' => $faker->userName,
                    'password' => $faker->password,
                ],
                'gender' => $faker->randomElement(['male', 'female']),
                'location' => [
                    'country' => 'Australia',
                    'city'    => $faker->city,
                ],
                'phone' => $faker->phoneNumber,
                'nat'   => 'AU',
            ];
        }, range(1, $count));
    }
}

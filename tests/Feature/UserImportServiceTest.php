<?php

namespace Tests\Feature;

use App\Exceptions\FetchUserException;
use App\Services\UserFetcherService;
use App\Services\UserImporterService;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class UserImportServiceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_imports_users_successfully(): void
    {
        Http::fake([
            '*' => Http::response([
                'results' => $this->fakeUsers(100),
            ], 200),
        ]);

        $entityManagerService = \Mockery::mock(EntityManagerInterface::class);
        $userFetcherService = \Mockery::mock(UserFetcherService::class);
        
        $userFetcherService->shouldReceive('fetch')->once()->andReturn($this->fakeUsers(100));
        $entityManagerService->shouldReceive('persist')->times(100);
        $entityManagerService->shouldReceive('flush')->once();
        
        $service = new UserImporterService($entityManagerService, $userFetcherService);
        $service->import();
        $this->assertTrue(true);
    }

    public function test_response_structure_is_correct(): void
    {
        Http::fake([
            '*' => Http::response([
                'results' => $this->fakeUsers(100),
            ], 200),
        ]);

        $userFetcherService = new UserFetcherService();
        $users = $userFetcherService->fetch();

        $this->assertCount(100, $users);

        foreach ($users as $user) {
            $this->assertArrayHasKey('name', $user);
            $this->assertArrayHasKey('first', $user['name']);
            $this->assertArrayHasKey('last',  $user['name']);

            $this->assertArrayHasKey('email', $user);

            $this->assertArrayHasKey('login', $user);
            $this->assertArrayHasKey('username', $user['login']);
            $this->assertArrayHasKey('password', $user['login']);

            $this->assertArrayHasKey('gender', $user);

            $this->assertArrayHasKey('location', $user);
            $this->assertArrayHasKey('country',  $user['location']);
            $this->assertArrayHasKey('city',     $user['location']);

            $this->assertArrayHasKey('phone', $user);
            $this->assertSame('AU', $user['nat']);
        }
    }

    public function test_fetch_fails_with_invalid_url(): void
    {
        Http::fake([
            '*' => fn () => throw new ConnectionException('Connection refused.'),
        ]);

        $this->expectException(ConnectionException::class);
        $this->expectExceptionMessage('Connection refused.');

        $service = new UserFetcherService();
        $service->fetch();
    }

    public function test_fetch_fails_with_malformed_response(): void
    {
        Http::fake([
            '*' => Http::response([
                'invalid_key' => [],
            ], 200),
        ]);

        $this->expectException(FetchUserException::class);
        $this->expectExceptionMessage('Malformed API response.');

        $service = new UserFetcherService();
        $service->fetch();
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

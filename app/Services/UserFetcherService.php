<?php

namespace App\Services;

use App\Exceptions\FetchUserException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class UserFetcherService
{
    /**
     * Parameters are optional to override the defaults set in configs 
     * to make it reusable across the app without doing anything.
     */
    public function fetch(?int $results = null, ?string $nat = null): array
    {
        try {
            $response = Http::get(config('services.user_provider.url'), [
                'results' => $results ?? config('services.user_provider.minimum_results'),
                'nat' => $nat ?? config('services.user_provider.default_nationality'),
            ]);
    
            if (!$response->successful()) {
                throw new FetchUserException('Failed to fetch users.');
            }
    
            $body = $response->json();
        } catch (ConnectionException) {
            throw new ConnectionException('Connection refused.');
        }
       
        if (!isset($body['results']) || !is_array($body['results'])) {
            throw new FetchUserException('Malformed API response.');
        }

        foreach ($body['results'] as $userData) {
            if (
                !isset(
                    $userData['name']['first'],
                    $userData['name']['last'],
                    $userData['email'],
                    $userData['login']['username'],
                    $userData['login']['password'],
                    $userData['gender'],
                    $userData['location']['country'],
                    $userData['location']['city'],
                    $userData['phone']
                )
            ) {
                throw new FetchUserException('Malformed user data.');
            }
        }

        return $body['results'];
    }
}

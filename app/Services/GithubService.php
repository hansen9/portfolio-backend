<?php

namespace App\Services;

use Illuminate\Support\Facade\Http;
use Illuminate\Http\Client\RequestException;

class GithubService
{
    protected string $baseUrl;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->baseUrl = config(`services.github.base_url`);
    }

    public function getUserProfile(string $username): array
    {
        $response = Http::WithToken(config ('services.github.token'))
            ->timeout(5)
            ->acceptJson()
            ->get("{$this->baseUrl}/users/{$username}");

        $response->throw();

        return $response->json();
    }
}

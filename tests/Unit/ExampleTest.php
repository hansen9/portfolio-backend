<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Services\GithubService;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }
    public function test_github_service(): void
    {
        Http::fake([
            'https://api.github.com/users/hansen9' => Http::response([
                'login' => 'hansen9',
                'name' => 'Test User',
                'bio' => 'Test bio',
            ], 200),
        ]);

        $githubService = new GithubService();
        $response = $githubService->getUserProfile('hansen9');

        $this->assertIsArray($response);
        $this->assertEquals('hansen9', $response['login']);
    }
}

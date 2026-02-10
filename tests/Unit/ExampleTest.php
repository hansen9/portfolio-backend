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
        $githubService = new GithubService();
        $response = $githubService->getLatestCommit('hansen9');

        $this->assertIsArray($response);
        // $this->assertArrayHasKey('name', $response);
        // $this->assertEquals('hansen9', $response['login']);

        dump($response);
    }
}

<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;
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
        Log($githubService->getUserProfile('hansen9'));
    }
}

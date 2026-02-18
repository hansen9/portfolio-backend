<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class GithubService
{
    protected string $baseUrl;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->baseUrl = config('services.github.base_url');
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
    public function getUserRepos(string $username): array
    {
        $response = Http::WithToken(config ('services.github.token'))
            ->timeout(5)
            ->acceptJson()
            ->get("{$this->baseUrl}/users/{$username}/repos",[
                'sort' => 'updated',
                'direction' => 'desc',
                'per_page' => 5,
            ]);

        $response->throw();

        return $response->json();
    }
    public function getLatestCommit(string $username): ?array
    {
        // TODO: change the personal access token to fine grained personal access token, make sure have read permission for `events`
        $response = Http::WithToken(config ('services.github.token'))
            ->timeout(5)
            ->acceptJson()
            ->get("{$this->baseUrl}/user/events",[
                'per_page' => 10
            ]);
        if ($response->failed()) {
            return null;
        }

        $lastPush = collect($response->json())
            ->filter(fn($event) =>
                $event['type'] === 'PushEvent' &&
                !empty($event['payload']['commits'])
            )
            ->first();
        if (!$lastPush) {
            return null;
        }

        // $commits = $lastPush;
        $commits = $lastPush['payload']['commits'];
        $latestCommit = end($commits);

        // return $commits;
        return [
            'repo'      => $lastPush['repo']['name'],
            'branch'    => str_replace('refs/heads/', '', $lastPush['payload']['ref']),
            'pushed_at' => $lastPush['created_at'],
            'head_sha'  => $lastPush['payload']['head'],  // SHA of the latest commit
            'commits'   => $commits,
            'latest'    => $latestCommit,
        ];
    }
}

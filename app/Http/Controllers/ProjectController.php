<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\GithubService;

class ProjectController extends Controller
{
    /**
     * Display a listing of all projects.
     */
    public function index(): JsonResponse
    {
        $projects = Project::all();
        return response()->json($projects);
    }

    /**
     * Display a specific project.
     */
    public function show(Project $project): JsonResponse
    {
        $githubService = new GithubService();
        $profile = $githubService->getUserProfile('hansen9');

        try{
            return response()->json($profile);
        } catch (RequestException $e) {
            report($e);

            throw new \RuntimeException('external services unavailable');
        }

    }

    /**
     * Store a newly created project in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'repo_url' => 'required|string|url',
            'demo_url' => 'required|string|url',
            'featured' => 'boolean',
        ]);

        $project = Project::create($validated);

        return response()->json($project, 201);
    }

    /**
     * Update the specified project in storage.
     */
    public function update(Request $request, Project $project): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'repo_url' => 'string|url',
            'demo_url' => 'string|url',
            'featured' => 'boolean',
        ]);

        $project->update($validated);

        return response()->json($project);
    }

    /**
     * Delete the specified project from storage.
     */
    public function destroy(Project $project): JsonResponse
    {
        $project->delete();

        return response()->json(null, 204);
    }
}

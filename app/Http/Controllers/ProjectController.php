<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Http\Requests\Api\StoreProjectRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('projects.index', compact('users'));
    }

    /**
     * GET /api/projects
     */
    public function apiIndex(): JsonResponse
    {
        $projects = Project::with(['members', 'owner'])
            ->withCount(['tasks', 'tasks as done_tasks_count' => fn($q) => $q->where('is_done', true)])
            ->orderBy('name')
            ->get()
            ->map(fn($p) => $this->formatProject($p));

        return response()->json($projects);
    }

    /**
     * POST /api/projects
     */
    public function apiStore(StoreProjectRequest $request): JsonResponse
    {
        $data    = $request->validated();
        $project = Project::create([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'status'      => $data['status'] ?? 'Planifié',
            'deadline'    => $data['deadline'] ?? null,
            'progress'    => 0,
            'owner_id'    => auth()->id(),
        ]);

        if (!empty($data['member_ids'])) {
            $project->members()->sync($data['member_ids']);
        }

        return response()->json($this->formatProject($project->load('members', 'owner')), 201);
    }

    /**
     * PATCH /api/projects/{id}
     */
    public function apiUpdate(Request $request, Project $project): JsonResponse
    {
        $data = $request->validate([
            'name'        => 'sometimes|string|max:150',
            'description' => 'nullable|string',
            'status'      => 'sometimes|in:Actif,Planifié,En pause,Terminé',
            'deadline'    => 'nullable|date',
            'progress'    => 'sometimes|integer|min:0|max:100',
            'member_ids'  => 'nullable|array',
            'member_ids.*'=> 'exists:users,id',
        ]);

        $project->update($data);

        if (array_key_exists('member_ids', $data)) {
            $project->members()->sync($data['member_ids'] ?? []);
        }

        return response()->json($this->formatProject($project->load('members', 'owner')));
    }

    /**
     * DELETE /api/projects/{id}
     */
    public function apiDestroy(Project $project): JsonResponse
    {
        $project->delete();
        return response()->json(['message' => 'Project deleted.']);
    }

    private function formatProject(Project $project): array
    {
        return [
            'id'           => $project->id,
            'name'         => $project->name,
            'description'  => $project->description,
            'status'       => $project->status,
            'status_class' => $project->status_class,
            'progress'     => $project->progress,
            'progress_fill_class' => $project->progress_fill_class,
            'deadline'     => $project->deadline?->format('Y-m-d'),
            'deadline_label'=> $project->deadline ? 'Fin: ' . $project->deadline->format('d M Y') : null,
            'team'         => $project->members->map(fn($u) => [
                'id'       => $u->id,
                'initials' => $u->initials,
                'name'     => $u->name,
            ]),
        ];
    }
}

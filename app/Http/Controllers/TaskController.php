<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Http\Requests\Api\StoreTaskRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    // ── Web view ──────────────────────────────────

    public function index()
    {
        $users    = User::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        return view('tasks.index', compact('users', 'projects'));
    }

    // ── REST API ──────────────────────────────────

    /**
     * GET /api/tasks
     * Returns tasks grouped by column for the Kanban board
     */
    public function apiIndex(Request $request): JsonResponse
    {
        $q = Task::with(['assignee', 'project'])->orderByRaw("FIELD(priority,'high','med','low')");

        if ($search = $request->search) {
            $q->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($project = $request->project_id) {
            $q->where('project_id', $project);
        }

        $tasks = $q->get();

        // Group by kanban column
        $columns = ['todo' => [], 'inprog' => [], 'review' => [], 'done' => []];
        foreach ($tasks as $task) {
            $col = $task->column;
            if (array_key_exists($col, $columns)) {
                $columns[$col][] = $this->formatTask($task);
            }
        }

        return response()->json($columns);
    }

    /**
     * POST /api/tasks
     */
    public function apiStore(StoreTaskRequest $request): JsonResponse
    {
        $data               = $request->validated();
        $data['created_by'] = auth()->id();
        $task               = Task::create($data);

        return response()->json($this->formatTask($task->load('assignee', 'project')), 201);
    }

    /**
     * PATCH /api/tasks/{id}
     * Used to update column (drag-drop), toggle done, or edit fields
     */
    public function apiUpdate(Request $request, Task $task): JsonResponse
    {
        $data = $request->validate([
            'title'       => 'sometimes|string|max:200',
            'description' => 'nullable|string',
            'category'    => 'sometimes|string|max:60',
            'priority'    => 'sometimes|in:high,med,low',
            'column'      => 'sometimes|in:todo,inprog,review,done',
            'due_date'    => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
            'is_done'     => 'sometimes|boolean',
        ]);

        // Auto-sync is_done with column
        if (isset($data['column'])) {
            $data['is_done'] = ($data['column'] === 'done');
        } elseif (isset($data['is_done'])) {
            $data['column'] = $data['is_done'] ? 'done' : ($task->column === 'done' ? 'todo' : $task->column);
        }

        $task->update($data);

        // Recalculate parent project progress
        if ($task->project_id) {
            $task->project->recalculateProgress();
        }

        return response()->json($this->formatTask($task->load('assignee', 'project')));
    }

    /**
     * DELETE /api/tasks/{id}
     */
    public function apiDestroy(Task $task): JsonResponse
    {
        $task->delete();
        return response()->json(['message' => 'Task deleted.']);
    }

    // ── Helpers ───────────────────────────────────

    private function formatTask(Task $task): array
    {
        return [
            'id'             => $task->id,
            'title'          => $task->title,
            'description'    => $task->description,
            'category'       => $task->category,
            'category_color' => $task->category_color,
            'priority'       => $task->priority,
            'priority_color' => $task->priority_color,
            'priority_label' => $task->priority_label,
            'column'         => $task->column,
            'due_date'       => $task->due_date?->format('Y-m-d'),
            'due_label'      => $task->due_label,
            'is_done'        => $task->is_done,
            'is_overdue'     => $task->isOverdue(),
            'assignee'       => $task->assignee ? [
                'id'       => $task->assignee->id,
                'name'     => $task->assignee->name,
                'initials' => $task->assignee->initials,
            ] : null,
            'project'        => $task->project ? [
                'id'   => $task->project->id,
                'name' => $task->project->name,
            ] : null,
        ];
    }
}

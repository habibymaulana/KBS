<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentTaskController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $tasks = Task::with('subject:id,name')
            ->where('assigned_to', $user->id)
            ->orderByRaw('COALESCE(due_date, created_at) asc')
            ->get()
            ->map(fn ($task) => [
                'title' => $task->title,
                'subject' => $task->subject->name ?? '-',
                'due' => optional($task->due_date)->format('d M Y') ?? '-',
                'status' => $task->status,
            ]);

        return view('student.tasks', compact('tasks'));
    }
}



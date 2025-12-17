<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherTaskController extends Controller
{
    public function create(Request $request): View
    {
        $teacher = $request->user();
        abort_unless($teacher->role === 'guru', 403);

        $subjects = Subject::where('teacher_id', $teacher->id)
            ->orderBy('name')
            ->get(['id', 'name']);

        $students = User::where('role', 'siswa')
            ->orderBy('name')
            ->get(['id', 'name', 'grade_level']);

        return view('teacher.tasks.create', compact('teacher', 'subjects', 'students'));
    }

    public function store(Request $request): RedirectResponse
    {
        $teacher = $request->user();
        abort_unless($teacher->role === 'guru', 403);

        $data = $request->validate([
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'assigned_to' => ['required', 'integer', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:tugas,kuis'],
            'due_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        Task::create([
            'subject_id' => $data['subject_id'],
            'assigned_by' => $teacher->id,
            'assigned_to' => $data['assigned_to'],
            'title' => $data['title'],
            'type' => $data['type'],
            'due_date' => $data['due_date'] ?? null,
            'status' => 'Belum',
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()
            ->route('dashboard')
            ->with('status', 'task-created');
    }
}



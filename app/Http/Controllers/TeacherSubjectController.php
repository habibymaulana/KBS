<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherSubjectController extends Controller
{
    public function index(Request $request): View
    {
        $teacher = $request->user();
        abort_unless($teacher->role === 'guru', 403);

        $subjects = Subject::with('students:id,name')
            ->where('teacher_id', $teacher->id)
            ->get();

        return view('teacher.subjects.index', compact('teacher', 'subjects'));
    }

    public function create(Request $request): View
    {
        $teacher = $request->user();
        abort_unless($teacher->role === 'guru', 403);

        $students = User::where('role', 'siswa')
            ->orderBy('name')
            ->get(['id', 'name', 'grade_level']);

        return view('teacher.subjects.create', compact('teacher', 'students'));
    }

    public function store(Request $request): RedirectResponse
    {
        $teacher = $request->user();
        abort_unless($teacher->role === 'guru', 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'class_code' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:500'],
            'student_ids' => ['array'],
            'student_ids.*' => ['integer', 'exists:users,id'],
        ]);

        $subject = Subject::create([
            'name' => $data['name'],
            'class_code' => $data['class_code'] ?? null,
            'description' => $data['description'] ?? null,
            'teacher_id' => $teacher->id,
        ]);

        if (!empty($data['student_ids'])) {
            $subject->students()->sync($data['student_ids']);
        }

        return redirect()
            ->route('teacher.subjects.index')
            ->with('status', 'subject-created');
    }
}



<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Task;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizOption;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function createSubject(Request $request): View|RedirectResponse
    {
        if ($request->user()->role !== 'guru') {
            return redirect()->route('dashboard');
        }

        $gradeLevels = User::where('role', 'siswa')
            ->whereNotNull('grade_level')
            ->distinct()
            ->orderBy('grade_level')
            ->pluck('grade_level');

        return view('teacher.subjects-create', compact('gradeLevels'));
    }

    public function storeSubject(Request $request): RedirectResponse
    {
        if ($request->user()->role !== 'guru') {
            return redirect()->route('dashboard');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'class_code' => ['nullable', 'string', 'max:50'],
            'grade_level' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
        ]);

        $subject = Subject::create([
            'name' => $validated['name'],
            'class_code' => $validated['class_code'] ?? null,
            'grade_level' => $validated['grade_level'],
            'description' => $validated['description'] ?? null,
            'teacher_id' => $request->user()->id,
        ]);

        // Enroll all students in that grade_level into this subject
        $studentIds = User::where('role', 'siswa')
            ->where('grade_level', $validated['grade_level'])
            ->pluck('id')
            ->all();

        if (! empty($studentIds)) {
            $subject->students()->syncWithoutDetaching($studentIds);
        }

        return redirect()->route('dashboard')->with('status', 'subject-created');
    }

    public function createTask(Request $request): View|RedirectResponse
    {
        if ($request->user()->role !== 'guru') {
            return redirect()->route('dashboard');
        }

        $teacher = $request->user();
        $subjects = $teacher->subjectsTaught()->get();

        $gradeLevels = User::where('role', 'siswa')
            ->whereNotNull('grade_level')
            ->distinct()
            ->orderBy('grade_level')
            ->pluck('grade_level');

        return view('teacher.tasks-create', compact('subjects', 'gradeLevels'));
    }

    public function storeTask(Request $request): RedirectResponse
    {
        if ($request->user()->role !== 'guru') {
            return redirect()->route('dashboard');
        }

        $validated = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'grade_level' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:tugas,kuis'],
            'due_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        // Create one task per student in the selected grade level
        $students = User::where('role', 'siswa')
            ->where('grade_level', $validated['grade_level'])
            ->pluck('id');

        foreach ($students as $studentId) {
        Task::create([
                'subject_id' => $validated['subject_id'],
                'assigned_by' => $request->user()->id,
                'assigned_to' => $studentId,
                'title' => $validated['title'],
                'type' => $validated['type'],
                'due_date' => $validated['due_date'] ?? null,
                'status' => 'Belum',
                'notes' => $validated['notes'] ?? null,
            ]);
        }

        return redirect()->route('dashboard')->with('status', 'task-created');
    }

    public function createQuiz(Request $request): View|RedirectResponse
    {
        if ($request->user()->role !== 'guru') {
            return redirect()->route('dashboard');
        }

        $teacher = $request->user();
        $subjects = $teacher->subjectsTaught()->get();

        $gradeLevels = User::where('role', 'siswa')
            ->whereNotNull('grade_level')
            ->distinct()
            ->orderBy('grade_level')
            ->pluck('grade_level');

        return view('teacher.quizzes-create', compact('subjects', 'gradeLevels'));
    }

    public function storeQuiz(Request $request): RedirectResponse
    {
        if ($request->user()->role !== 'guru') {
            return redirect()->route('dashboard');
        }

        $data = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'grade_level' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:255'],
            'due_at' => ['nullable', 'date'],

            'questions' => ['required', 'array', 'min:1'],
            'questions.*.text' => ['required', 'string'],
            'questions.*.options' => ['required', 'array', 'min:2'],
            'questions.*.options.*.text' => ['required', 'string'],
            'questions.*.correct_index' => ['required', 'integer', 'min:0'],
        ]);

        $quiz = Quiz::create([
            'subject_id' => $data['subject_id'],
            'created_by' => $request->user()->id,
            'title' => $data['title'],
            'grade_level' => $data['grade_level'],
            'due_at' => $data['due_at'] ?? null,
            'is_active' => true,
        ]);

        foreach ($data['questions'] as $qIndex => $qData) {
            $question = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question_text' => $qData['text'],
                'points' => 10,
            ]);

            foreach ($qData['options'] as $oIndex => $opt) {
                QuizOption::create([
                    'question_id' => $question->id,
                    'option_text' => $opt['text'],
                    'is_correct' => $oIndex === $qData['correct_index'],
                ]);
            }
        }

        // Opsional: buat task ringkasan kuis untuk semua siswa di kelas tersebut
        $students = User::where('role', 'siswa')
            ->where('grade_level', $data['grade_level'])
            ->pluck('id');

        foreach ($students as $studentId) {
            Task::create([
                'subject_id' => $data['subject_id'],
                'assigned_by' => $request->user()->id,
                'assigned_to' => $studentId,
                'title' => $data['title'],
                'type' => 'kuis',
                'due_date' => $data['due_at'] ?? null,
                'status' => 'Belum',
                'notes' => 'Kuis pilihan ganda',
            ]);
        }

        return redirect()->route('dashboard')->with('status', 'quiz-created');
    }
}


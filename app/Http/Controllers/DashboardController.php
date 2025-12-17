<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Task;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        if ($user->role === 'guru') {
            $subjects = Subject::withCount('students')
                ->with('students:id,name')
                ->where('teacher_id', $user->id)
                ->get();

            $gradebook = Task::with(['student:id,name', 'subject:id,name'])
                ->where('assigned_by', $user->id)
                ->latest()
                ->take(10)
                ->get()
                ->map(fn ($task) => [
                    'student' => $task->student->name ?? '-',
                    'subject' => $task->subject->name ?? '-',
                    'task' => $task->title,
                    'score' => $task->score,
                ]);

            $quizIdeas = [
                ['title' => 'Kuis Derivatif', 'type' => 'Pilihan Ganda', 'questions' => 10],
                ['title' => 'Eksperimen Lensa', 'type' => 'Kuis Visual', 'questions' => 8],
                ['title' => 'Kuis Logika', 'type' => 'Interaktif', 'questions' => 6],
            ];

            return view('teacher.dashboard', compact('user', 'subjects', 'gradebook', 'quizIdeas'));
        }

        $subjects = $user->subjectsEnrolled()
            ->with('teacher:id,name')
            ->get()
            ->map(function ($subject) {
                $colors = [
                    'from-indigo-500 to-blue-500',
                    'from-emerald-500 to-teal-400',
                    'from-pink-500 to-rose-400',
                    'from-orange-500 to-amber-400',
                ];
                return [
                    'name' => $subject->name,
                    'teacher' => $subject->teacher?->name ?? '-',
                    'progress' => rand(50, 95),
                    'color' => $colors[array_rand($colors)],
                ];
            });

        $tasks = Task::with('subject:id,name')
            ->where('assigned_to', $user->id)
            ->latest('due_date')
            ->take(10)
            ->get()
            ->map(fn ($task) => [
                'title' => $task->title,
                'subject' => $task->subject->name ?? '-',
                'due' => optional($task->due_date)->format('d M') ?? '-',
                'status' => $task->status,
            ]);

        $quizzes = $tasks->where('status', '!=', 'Selesai')
            ->take(3)
            ->values()
            ->map(function ($task) {
                $colors = [
                    'bg-gradient-to-r from-purple-500 to-indigo-500',
                    'bg-gradient-to-r from-orange-500 to-amber-400',
                    'bg-gradient-to-r from-emerald-500 to-teal-400',
                ];
                return [
                    'title' => $task['title'],
                    'type' => 'Kuis',
                    'time' => '10 menit',
                    'color' => $colors[array_rand($colors)],
                ];
            });

        $attempts = QuizAttempt::where('student_id', $user->id)->get();
        $avgPercent = $attempts->count()
            ? round($attempts->reduce(fn ($carry, $a) => $carry + ($a->max_score > 0 ? ($a->score / $a->max_score) * 100 : 0), 0) / $attempts->count())
            : 0;

        $quizzesCompleted = $attempts->count();
        $points = (int) ($user->points ?? 0);
        $pointsProgress = min(100, (int) round(($points / 600) * 100));

        $stats = [
            ['label' => 'Rata-rata nilai', 'value' => $avgPercent],
            ['label' => 'Kuis Selesai', 'value' => min(100, $quizzesCompleted * 10)],
            ['label' => 'Progres level', 'value' => $pointsProgress],
        ];

        $achievements = [];

        if ($avgPercent >= 90) {
            $achievements[] = [
                'badge' => 'Top Score',
                'desc' => 'Rata-rata kuis di atas 90%',
                'score' => $avgPercent,
            ];
        }

        if (($user->streak_days ?? 0) >= 3) {
            $achievements[] = [
                'badge' => 'Belajar Rutin',
                'desc' => "Streak {$user->streak_days} hari",
                'score' => $user->streak_days,
            ];
        }

        if ($quizzesCompleted >= 3) {
            $achievements[] = [
                'badge' => 'Quiz Explorer',
                'desc' => 'Sudah menyelesaikan beberapa kuis',
                'score' => $quizzesCompleted,
            ];
        }

        if (empty($achievements)) {
            $achievements[] = [
                'badge' => 'Mulai Perjalanan',
                'desc' => 'Kerjakan kuis pertama untuk membuka badge lain.',
                'score' => 0,
            ];
        }

        return view('student.dashboard', compact('user', 'subjects', 'tasks', 'quizzes', 'achievements', 'stats'));
    }
}


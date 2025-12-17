<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentQuizController extends Controller
{
    public function index(Request $request): View
    {
        $student = $request->user();

        $quizzes = Quiz::with('subject')
            ->where('is_active', true)
            ->where(function ($q) use ($student) {
                $q->whereNull('grade_level')
                    ->orWhere('grade_level', $student->grade_level);
            })
            ->latest()
            ->get();

        $attempts = QuizAttempt::where('student_id', $student->id)
            ->get()
            ->keyBy('quiz_id');

        return view('student.quizzes-index', compact('quizzes', 'attempts'));
    }

    public function show(Request $request, Quiz $quiz): View|RedirectResponse
    {
        $student = $request->user();

        if ($quiz->grade_level && $quiz->grade_level !== $student->grade_level) {
            return redirect()->route('student.quizzes');
        }

        // Jika sudah pernah mengerjakan, langsung arahkan ke halaman hasil terakhir
        $existingAttempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', $student->id)
            ->latest()
            ->first();

        if ($existingAttempt) {
            return redirect()->route('student.quizzes.result', $existingAttempt);
        }

        // Jika sudah pernah mengerjakan, langsung ke halaman hasil
        $existingAttempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', $student->id)
            ->latest()
            ->first();

        if ($existingAttempt) {
            return redirect()->route('student.quizzes.result', $existingAttempt);
        }

        $quiz->load('questions.options', 'subject');

        return view('student.quizzes-take', compact('quiz'));
    }

    public function submit(Request $request, Quiz $quiz): RedirectResponse
    {
        $student = $request->user();

        $quiz->load('questions.options');

        $data = $request->validate([
            'answers' => ['required', 'array'],
        ]);

        $answersInput = $data['answers'];

        $score = 0;
        $maxScore = 0;
        $correct = 0;
        $wrong = 0;

        foreach ($quiz->questions as $question) {
            $maxScore += $question->points;

            $selectedOptionId = isset($answersInput[$question->id])
                ? (int) $answersInput[$question->id]
                : null;

            $correctOption = $question->options->firstWhere('is_correct', true);
            $isCorrect = $selectedOptionId && $correctOption && $selectedOptionId === $correctOption->id;

            if ($isCorrect) {
                $score += $question->points;
                $correct++;
            } else {
                $wrong++;
            }
        }

        $attempt = DB::transaction(function () use ($quiz, $student, $score, $maxScore, $correct, $wrong, $answersInput) {
            $attempt = QuizAttempt::create([
                'quiz_id' => $quiz->id,
                'student_id' => $student->id,
                'score' => $score,
                'max_score' => $maxScore,
                'correct_count' => $correct,
                'wrong_count' => $wrong,
            ]);

            foreach ($quiz->questions as $question) {
                $selectedOptionId = isset($answersInput[$question->id])
                    ? (int) $answersInput[$question->id]
                    : null;

                $correctOption = $question->options->firstWhere('is_correct', true);
                $isCorrect = $selectedOptionId && $correctOption && $selectedOptionId === $correctOption->id;

                QuizAnswer::create([
                    'attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'option_id' => $selectedOptionId,
                    'is_correct' => $isCorrect,
                ]);
            }

            // Update related task (if exists) for this quiz
            Task::where('assigned_to', $student->id)
                ->where('type', 'kuis')
                ->where('title', $quiz->title)
                ->where('status', 'Belum')
                ->update([
                    'status' => 'Selesai',
                    'score' => $score,
                ]);

            return $attempt;
        });

        // Gamification: poin berdasarkan hasil kuis
        $percent = $maxScore > 0 ? round(($score / $maxScore) * 100) : 0;

        if ($percent >= 80) {
            $student->addPoints(50);
        } elseif ($percent >= 50) {
            $student->addPoints(20);
        } else {
            $student->addPoints(5);
        }

        return redirect()->route('student.quizzes.result', $attempt);
    }

    public function result(QuizAttempt $attempt, Request $request): View|RedirectResponse
    {
        if ($attempt->student_id !== $request->user()->id) {
            return redirect()->route('student.quizzes');
        }

        $attempt->load('quiz.questions.options', 'answers');

        return view('student.quizzes-result', compact('attempt'));
    }
}



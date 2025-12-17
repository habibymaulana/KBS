import React, { useEffect, useMemo, useState } from 'react';
import ReactDOM from 'react-dom/client';
import {
    BookOpen,
    CheckCircle2,
    Clock,
    Medal,
    PlayCircle,
    Star,
    Trophy,
    Zap,
} from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';
import {
    Area,
    AreaChart,
    Bar,
    BarChart,
    CartesianGrid,
    Line,
    LineChart,
    PolarAngleAxis,
    RadialBar,
    RadialBarChart,
    ResponsiveContainer,
    Tooltip,
    XAxis,
    YAxis,
} from 'recharts';

type Course = {
    id: number;
    title: string;
    category: string;
    thumbnail: string;
    progress: number;
    hours: number;
};

type Lesson = {
    id: number;
    title: string;
    duration: string;
    completed: boolean;
};

type QuizQuestion = {
    id: number;
    question: string;
    options: string[];
    correctIndex: number;
};

type Badge = {
    id: string;
    name: string;
    description: string;
    icon: React.ReactNode;
};

type GamificationState = {
    points: number;
    level: 'Bronze' | 'Silver' | 'Gold' | 'Platinum';
    streakDays: number;
    badges: Badge[];
};

const demoCourses: Course[] = [
    {
        id: 1,
        title: 'Matematika Dasar',
        category: 'STEM',
        thumbnail: 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=900&q=80',
        progress: 72,
        hours: 14,
    },
    {
        id: 2,
        title: 'Bahasa Inggris Komunikatif',
        category: 'Language',
        thumbnail: 'https://images.unsplash.com/photo-1523580846011-d3a5bc25702b?auto=format&fit=crop&w=900&q=80',
        progress: 38,
        hours: 6,
    },
];

const demoLessons: Lesson[] = [
    { id: 1, title: 'Pengenalan Persamaan Linear', duration: '12 menit', completed: true },
    { id: 2, title: 'Latihan Soal Persamaan Linear', duration: '18 menit', completed: false },
    { id: 3, title: 'Quiz Persamaan Linear', duration: '10 menit', completed: false },
];

const demoQuestions: QuizQuestion[] = [
    {
        id: 1,
        question: 'Hasil dari 2x + 3 = 11 adalah?',
        options: ['x = 4', 'x = 3', 'x = 5', 'x = 2'],
        correctIndex: 0,
    },
    {
        id: 2,
        question: 'Bentuk persamaan garis lurus adalah?',
        options: ['ax + by = c', 'x^2 + y^2 = r^2', 'a/b = c/d', 'ab = ba'],
        correctIndex: 0,
    },
];

const computeLevel = (points: number): GamificationState['level'] => {
    if (points <= 100) return 'Bronze';
    if (points <= 300) return 'Silver';
    if (points <= 600) return 'Gold';
    return 'Platinum';
};

const DashboardShell: React.FC = () => {
    const [selectedCourse] = useState<Course>(demoCourses[0]);
    const [lessons, setLessons] = useState<Lesson[]>(demoLessons);
    const [quizIndex, setQuizIndex] = useState(0);
    const [selectedOption, setSelectedOption] = useState<number | null>(null);
    const [score, setScore] = useState(0);
    const [timeLeft, setTimeLeft] = useState(60);
    const [quizFinished, setQuizFinished] = useState(false);
    const [showAchievementModal, setShowAchievementModal] = useState(false);
    const [newBadge, setNewBadge] = useState<Badge | null>(null);
    const [showLevelUpModal, setShowLevelUpModal] = useState(false);

    const [gami, setGami] = useState<GamificationState>({
        points: 120,
        level: 'Silver',
        streakDays: 5,
        badges: [],
    });

    // Simple demo badges
    const badgeDefinitions: Record<string, Badge> = useMemo(
        () => ({
            quick: {
                id: 'quick',
                name: 'Quick Learner',
                description: 'Selesaikan quiz dengan cepat',
                icon: <Zap className="w-5 h-5 text-yellow-400" />,
            },
            perfect: {
                id: 'perfect',
                name: 'Perfect Score',
                description: 'Mendapatkan skor 100%',
                icon: <Star className="w-5 h-5 text-amber-400" />,
            },
        }),
        []
    );

    // Timer countdown
    useEffect(() => {
        if (quizFinished) return;
        if (timeLeft <= 0) {
            handleSubmitQuiz();
            return;
        }
        const t = setTimeout(() => setTimeLeft((t) => t - 1), 1000);
        return () => clearTimeout(t);
    }, [timeLeft, quizFinished]);

    const handleToggleLesson = (id: number) => {
        setLessons((prev) =>
            prev.map((l) => (l.id === id ? { ...l, completed: !l.completed } : l))
        );

        // +10 points for completing lesson
        setGami((prev) => {
            const updated = { ...prev, points: prev.points + 10 };
            const newLevel = computeLevel(updated.points);
            updated.level = newLevel;
            if (newLevel !== prev.level) {
                setShowLevelUpModal(true);
            }
            return updated;
        });
    };

    const handleNextQuestion = () => {
        if (selectedOption === demoQuestions[quizIndex].correctIndex) {
            setScore((s) => s + 1);
        }
        setSelectedOption(null);
        if (quizIndex < demoQuestions.length - 1) {
            setQuizIndex((i) => i + 1);
        } else {
            handleSubmitQuiz();
        }
    };

    const handleSubmitQuiz = () => {
        if (quizFinished) return;
        const finalScore =
            selectedOption === demoQuestions[quizIndex].correctIndex
                ? score + 1
                : score;
        setScore(finalScore);
        setQuizFinished(true);

        const percent = Math.round((finalScore / demoQuestions.length) * 100);

        setGami((prev) => {
            let addedPoints = 0;
            if (percent >= 80) addedPoints += 50;
            if (prev.streakDays >= 7) addedPoints += 25;

            const updated: GamificationState = {
                ...prev,
                points: prev.points + addedPoints,
                badges: [...prev.badges],
            };

            if (percent === 100 && !prev.badges.find((b) => b.id === 'perfect')) {
                const badge = badgeDefinitions.perfect;
                updated.badges.push(badge);
                setNewBadge(badge);
                setShowAchievementModal(true);
            }

            const newLevel = computeLevel(updated.points);
            if (newLevel !== prev.level) {
                updated.level = newLevel;
                setShowLevelUpModal(true);
            }

            return updated;
        });
    };

    const currentQuestion = demoQuestions[quizIndex];
    const quizPercent = Math.round((score / demoQuestions.length) * 100);

    // Simple chart demo data
    const hoursData = [
        { day: 'Sen', value: 1.5 },
        { day: 'Sel', value: 2 },
        { day: 'Rab', value: 1 },
        { day: 'Kam', value: 2.5 },
        { day: 'Jum', value: 1.8 },
    ];

    const quizHistory = [
        { name: 'Kuis 1', score: 70 },
        { name: 'Kuis 2', score: 85 },
        { name: 'Kuis 3', score: 90 },
    ];

    const pointsOverTime = [
        { name: 'Minggu 1', points: 80 },
        { name: 'Minggu 2', points: 140 },
        { name: 'Minggu 3', points: 220 },
        { name: 'Minggu 4', points: gami.points },
    ];

    return (
        <div className="space-y-6">
            {/* Hero + sidebar layout */}
            <div className="grid lg:grid-cols-4 gap-4">
                <div className="lg:col-span-3 space-y-4">
                    <div className="bg-white border border-slate-200 rounded-2xl px-6 py-5 shadow-sm flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p className="text-xs text-violet-600 font-semibold uppercase tracking-wide">
                                Selamat datang kembali
                            </p>
                            <h1 className="text-2xl font-semibold text-slate-900">
                                Lanjutkan belajar, kemajuanmu terlihat bagus!
                            </h1>
                            <div className="mt-2 flex gap-6 text-xs text-slate-600">
                                <div className="flex items-center gap-1">
                                    <BookOpen className="w-4 h-4 text-violet-500" />
                                    <span>{demoCourses.length} courses</span>
                                </div>
                                <div className="flex items-center gap-1">
                                    <Clock className="w-4 h-4 text-emerald-500" />
                                    <span>{selectedCourse.hours} jam belajar</span>
                                </div>
                                <div className="flex items-center gap-1">
                                    <Zap className="w-4 h-4 text-amber-500" />
                                    <span>{gami.streakDays} day streak</span>
                                </div>
                            </div>
                        </div>
                        <div className="flex gap-4 text-xs">
                            <div className="text-center">
                                <p className="text-slate-500">Points</p>
                                <p className="text-lg font-semibold text-violet-600">{gami.points}</p>
                            </div>
                            <div className="text-center">
                                <p className="text-slate-500">Level</p>
                                <p className="text-lg font-semibold text-slate-800">{gami.level}</p>
                            </div>
                        </div>
                    </div>

                    {/* Courses list */}
                    <div className="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-3">
                        <div className="flex items-center justify-between">
                            <h2 className="text-sm font-semibold text-slate-900">Course kamu</h2>
                            <span className="text-xs text-slate-500">Lanjutkan dari terakhir berhenti</span>
                        </div>
                        <div className="grid md:grid-cols-2 gap-4">
                            {demoCourses.map((course) => (
                                <div
                                    key={course.id}
                                    className="rounded-xl border border-slate-200 overflow-hidden bg-slate-50"
                                >
                                    <img
                                        src={course.thumbnail}
                                        alt={course.title}
                                        className="h-32 w-full object-cover"
                                    />
                                    <div className="p-4 space-y-2">
                                        <p className="text-xs text-violet-600 font-medium">
                                            {course.category}
                                        </p>
                                        <h3 className="text-sm font-semibold text-slate-900">
                                            {course.title}
                                        </h3>
                                        <div className="mt-2">
                                            <div className="flex justify-between text-[11px] text-slate-500 mb-1">
                                                <span>Progress</span>
                                                <span>{course.progress}%</span>
                                            </div>
                                            <div className="h-1.5 rounded-full bg-slate-200">
                                                <div
                                                    className="h-1.5 rounded-full bg-violet-500"
                                                    style={{ width: `${course.progress}%` }}
                                                />
                                            </div>
                                        </div>
                                        <div className="mt-3 flex gap-2">
                                            <button className="btn btn-sm btn-primary flex items-center gap-1 text-xs">
                                                <PlayCircle className="w-3 h-3" />
                                                Continue Learning
                                            </button>
                                            <button className="btn btn-sm btn-outline-secondary text-xs">
                                                View Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>

                {/* Sidebar profile & badges */}
                <div className="space-y-4">
                    <div className="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm space-y-3">
                        <div className="flex items-center gap-3">
                            <div className="w-10 h-10 rounded-full bg-gradient-to-br from-violet-500 to-indigo-500 flex items-center justify-center text-white text-sm font-semibold">
                                U
                            </div>
                            <div>
                                <p className="text-sm font-semibold text-slate-900">User Siswa</p>
                                <p className="text-xs text-slate-500">Level {gami.level}</p>
                            </div>
                        </div>
                        <div className="grid grid-cols-3 gap-3 text-center text-[11px] text-slate-600">
                            <div>
                                <p className="font-semibold text-slate-900">{gami.points}</p>
                                <p>Points</p>
                            </div>
                            <div>
                                <p className="font-semibold text-slate-900">{gami.badges.length}</p>
                                <p>Badges</p>
                            </div>
                            <div>
                                <p className="font-semibold text-slate-900">{gami.streakDays} hari</p>
                                <p>Streak</p>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm space-y-3">
                        <div className="flex items-center justify-between">
                            <p className="text-sm font-semibold text-slate-900">Achievement</p>
                            <Medal className="w-4 h-4 text-amber-500" />
                        </div>
                        {gami.badges.length === 0 ? (
                            <p className="text-xs text-slate-500">
                                Raih badge pertamamu dengan menyelesaikan quiz atau course.
                            </p>
                        ) : (
                            <div className="space-y-2">
                                {gami.badges.slice(-3).map((badge) => (
                                    <div
                                        key={badge.id}
                                        className="flex items-center justify-between text-xs"
                                    >
                                        <div className="flex items-center gap-2">
                                            {badge.icon}
                                            <span className="font-semibold text-slate-800">
                                                {badge.name}
                                            </span>
                                        </div>
                                        <span className="text-slate-500">{badge.description}</span>
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>

                    {/* Simple charts */}
                    <div className="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm space-y-4">
                        <p className="text-xs font-semibold text-slate-900 flex items-center gap-1">
                            <Clock className="w-4 h-4 text-violet-500" />
                            Learning Analytics
                        </p>
                        <div className="h-24">
                            <ResponsiveContainer width="100%" height="100%">
                                <LineChart data={hoursData}>
                                    <CartesianGrid strokeDasharray="3 3" vertical={false} />
                                    <XAxis dataKey="day" hide />
                                    <YAxis hide />
                                    <Tooltip />
                                    <Line type="monotone" dataKey="value" stroke="#7c3aed" dot={false} />
                                </LineChart>
                            </ResponsiveContainer>
                        </div>
                        <div className="h-24">
                            <ResponsiveContainer width="100%" height="100%">
                                <BarChart data={quizHistory}>
                                    <CartesianGrid strokeDasharray="3 3" vertical={false} />
                                    <XAxis dataKey="name" hide />
                                    <YAxis hide />
                                    <Tooltip />
                                    <Bar dataKey="score" fill="#22c55e" radius={[4, 4, 0, 0]} />
                                </BarChart>
                            </ResponsiveContainer>
                        </div>
                        <div className="h-24">
                            <ResponsiveContainer width="100%" height="100%">
                                <AreaChart data={pointsOverTime}>
                                    <defs>
                                        <linearGradient id="colorPoints" x1="0" y1="0" x2="0" y2="1">
                                            <stop offset="5%" stopColor="#6366f1" stopOpacity={0.8} />
                                            <stop offset="95%" stopColor="#6366f1" stopOpacity={0} />
                                        </linearGradient>
                                    </defs>
                                    <CartesianGrid strokeDasharray="3 3" vertical={false} />
                                    <XAxis dataKey="name" hide />
                                    <YAxis hide />
                                    <Tooltip />
                                    <Area
                                        type="monotone"
                                        dataKey="points"
                                        stroke="#6366f1"
                                        fillOpacity={1}
                                        fill="url(#colorPoints)"
                                    />
                                </AreaChart>
                            </ResponsiveContainer>
                        </div>
                    </div>
                </div>
            </div>

            {/* Course detail + quiz section */}
            <div className="grid lg:grid-cols-3 gap-4">
                {/* Course detail + lessons */}
                <div className="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm lg:col-span-2 space-y-4">
                    <div className="aspect-video rounded-xl overflow-hidden bg-slate-200 mb-2">
                        <iframe
                            className="w-full h-full"
                            src="https://www.youtube.com/embed/7LZ5qJ9r4k0"
                            title="Video pembelajaran"
                            allowFullScreen
                        />
                    </div>
                    <h2 className="text-sm font-semibold text-slate-900">
                        {selectedCourse.title} • Detail Course
                    </h2>
                    <p className="text-xs text-slate-600">
                        Pelajari materi secara bertahap, centang setiap lesson yang sudah kamu
                        selesaikan untuk mendapatkan poin.
                    </p>
                    <div className="mt-3 space-y-2">
                        {lessons.map((lesson) => (
                            <button
                                key={lesson.id}
                                onClick={() => handleToggleLesson(lesson.id)}
                                className="w-full flex items-center justify-between rounded-lg border border-slate-200 px-3 py-2 text-xs hover:bg-slate-50 transition"
                            >
                                <div className="flex items-center gap-2">
                                    <CheckCircle2
                                        className={`w-4 h-4 ${
                                            lesson.completed
                                                ? 'text-emerald-500'
                                                : 'text-slate-300'
                                        }`}
                                    />
                                    <div className="text-left">
                                        <p className="font-semibold text-slate-900">
                                            {lesson.title}
                                        </p>
                                        <p className="text-slate-500">{lesson.duration}</p>
                                    </div>
                                </div>
                                {lesson.completed && (
                                    <span className="text-[10px] px-2 py-1 rounded-full bg-emerald-50 text-emerald-700 font-semibold">
                                        Selesai
                                    </span>
                                )}
                            </button>
                        ))}
                    </div>
                </div>

                {/* Quiz card */}
                <div className="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-4">
                    <div className="flex items-center justify-between">
                        <p className="text-sm font-semibold text-slate-900 flex items-center gap-2">
                            <Trophy className="w-4 h-4 text-amber-500" />
                            Quiz interaktif
                        </p>
                        <span className="text-xs text-slate-500">
                            {quizFinished ? 'Selesai' : `Sisa waktu: ${timeLeft}s`}
                        </span>
                    </div>

                    <div className="text-xs text-slate-600">
                        Pertanyaan {quizIndex + 1} / {demoQuestions.length}
                    </div>

                    <div className="text-sm font-semibold text-slate-900">
                        {currentQuestion.question}
                    </div>

                    <div className="space-y-2">
                        {currentQuestion.options.map((opt, idx) => (
                            <button
                                key={idx}
                                disabled={quizFinished}
                                onClick={() => setSelectedOption(idx)}
                                className={`w-full text-left text-xs px-3 py-2 rounded-lg border transition ${
                                    selectedOption === idx
                                        ? 'border-violet-500 bg-violet-50'
                                        : 'border-slate-200 hover:bg-slate-50'
                                }`}
                            >
                                {opt}
                            </button>
                        ))}
                    </div>

                    <div className="flex items-center justify-between pt-2">
                        <button
                            disabled={quizFinished}
                            onClick={handleNextQuestion}
                            className="btn btn-sm btn-primary text-xs"
                        >
                            {quizIndex === demoQuestions.length - 1 ? 'Submit Quiz' : 'Next Question'}
                        </button>
                        {quizFinished && (
                            <span className="text-xs font-medium text-emerald-600">
                                Skor: {quizPercent}%
                            </span>
                        )}
                    </div>

                    {quizFinished && (
                        <div className="pt-3 border-t border-slate-200">
                            <div className="h-24 flex items-center justify-center">
                                <ResponsiveContainer width="100%" height="100%">
                                    <RadialBarChart
                                        innerRadius="70%"
                                        outerRadius="100%"
                                        data={[{ name: 'Score', value: quizPercent }]}
                                        startAngle={90}
                                        endAngle={-270}
                                    >
                                        <PolarAngleAxis
                                            type="number"
                                            domain={[0, 100]}
                                            tick={false}
                                        />
                                        <RadialBar
                                            background
                                            dataKey="value"
                                            cornerRadius={999}
                                            fill="#6366f1"
                                        />
                                    </RadialBarChart>
                                </ResponsiveContainer>
                            </div>
                            <p className="text-[11px] text-slate-600 mt-2">
                                Dapatkan poin tambahan dengan mengulang kuis dan meningkatkan skor.
                            </p>
                        </div>
                    )}
                </div>
            </div>

            {/* Achievement & level-up modals */}
            <AnimatePresence>
                {showAchievementModal && newBadge && (
                    <motion.div
                        className="fixed inset-0 z-40 flex items-center justify-center bg-black/40"
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        exit={{ opacity: 0 }}
                    >
                        <motion.div
                            className="bg-white rounded-2xl p-6 max-w-sm w-full shadow-xl"
                            initial={{ scale: 0.9, opacity: 0 }}
                            animate={{ scale: 1, opacity: 1 }}
                            exit={{ scale: 0.9, opacity: 0 }}
                        >
                            <div className="flex items-center gap-3 mb-3">
                                <Medal className="w-6 h-6 text-amber-500" />
                                <h3 className="text-sm font-semibold text-slate-900">
                                    Badge baru didapat!
                                </h3>
                            </div>
                            <div className="flex items-center gap-2 text-sm">
                                {newBadge.icon}
                                <div>
                                    <p className="font-semibold text-slate-900">
                                        {newBadge.name}
                                    </p>
                                    <p className="text-xs text-slate-600">
                                        {newBadge.description}
                                    </p>
                                </div>
                            </div>
                            <div className="mt-4 flex justify-end">
                                <button
                                    className="btn btn-sm btn-primary text-xs"
                                    onClick={() => setShowAchievementModal(false)}
                                >
                                    Keren!
                                </button>
                            </div>
                        </motion.div>
                    </motion.div>
                )}
            </AnimatePresence>

            <AnimatePresence>
                {showLevelUpModal && (
                    <motion.div
                        className="fixed inset-0 z-30 flex items-center justify-center pointer-events-none"
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        exit={{ opacity: 0 }}
                    >
                        <motion.div
                            className="bg-violet-600 text-white rounded-full px-4 py-2 text-xs font-semibold shadow-lg pointer-events-auto"
                            initial={{ y: 40, opacity: 0 }}
                            animate={{ y: 0, opacity: 1 }}
                            exit={{ y: 40, opacity: 0 }}
                            onAnimationComplete={() => {
                                setTimeout(() => setShowLevelUpModal(false), 1500);
                            }}
                        >
                            Level up! Kamu sekarang di level {gami.level}.
                        </motion.div>
                    </motion.div>
                )}
            </AnimatePresence>
        </div>
    );
};

const rootElement = document.getElementById('react-root');

if (rootElement) {
    const root = ReactDOM.createRoot(rootElement);
    root.render(
        <React.StrictMode>
            <DashboardShell />
        </React.StrictMode>
    );
}



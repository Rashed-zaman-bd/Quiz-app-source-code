<?php
session_start();
require_once 'db.php';


if (isset($_GET['reset']) || !isset($_SESSION['quiz_step'])) {
    $_SESSION['quiz_step'] = 1;
    $_SESSION['score'] = 0;


    $stmt = $pdo->query("SELECT * FROM questions ORDER BY id DESC LIMIT 20");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $_SESSION['quiz_questions_data'] = $data;
    $_SESSION['quiz_ids'] = array_column($data, 'id');

    if (isset($_GET['reset'])) {
        header("Location: index.php");
        exit;
    }
}

$isFinished = (isset($_GET['status']) && $_GET['status'] === 'finished');
$currentStep = $_SESSION['quiz_step'];
$showLoginNotice = false;
$question = null;

if (!$isFinished) {
    if ($currentStep > 10 && !isset($_SESSION['user_id'])) {
        $showLoginNotice = true;
    } else {
        $currentQuestionIndex = $currentStep - 1;

        if (isset($_SESSION['quiz_ids'][$currentQuestionIndex])) {
            $questionId = $_SESSION['quiz_ids'][$currentQuestionIndex];
            $stmt = $pdo->prepare("SELECT * FROM questions WHERE id = ?");
            $stmt->execute([$questionId]);
            $question = $stmt->fetch();
        } else {
            $isFinished = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="/main/img/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/main/main.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>quiz app</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

</head>

<body>
    <section class="top-navbar">
        <nav
            class="max-w-6xl mx-auto bg-white py-2 px-6 shadow-md flex items-center justify-between font-google sticky top-0 z-50">

            <div class="flex items-center gap-4">
                <button id="mobile-menu-btn" class="lg:hidden text-gray-700 focus:outline-none">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <div class="flex items-center gap-3">
                    <img src="/main/img/new logo.png" alt="Logo" class="w-10 h-10 object-contain">
                    <h1 class="text-xl font-bold tracking-tight text-gray-800">
                        <a href="index.php">প্রশ্নমালা</a>
                    </h1>
                </div>
            </div>

            <ul class="hidden lg:flex items-center gap-8 text-gray-600 font-medium">
                <li class="hover:text-amber-600 cursor-pointer transition">
                    <a href="/">Home</a>
                </li>

                <li class="group relative py-4 hover:text-amber-600 cursor-pointer transition">
                    <span class="flex items-center">
                        Quiz Items
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </span>
                    <ul
                        class="absolute left-0 top-full hidden group-hover:block bg-white shadow-xl rounded-md py-2 w-56 border border-gray-100 text-gray-700 z-50">
                        <li
                            class="group/sub relative px-4 py-2 hover:bg-amber-50 hover:text-amber-600 flex justify-between items-center">
                            General Knowledge
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                            <ul
                                class="absolute left-full top-0 hidden group-hover/sub:block bg-white shadow-xl rounded-md py-2 w-64 border border-gray-100">
                                <li class="px-4 py-2 hover:bg-amber-50">General Knowledge MCQ</li>
                                <li class="px-4 py-2 hover:bg-amber-50">General Knowledge Notes</li>
                            </ul>
                        </li>
                        <li
                            class="group/sub relative px-4 py-2 hover:bg-amber-50 hover:text-amber-600 flex justify-between items-center">
                            Bangla
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                            <ul
                                class="absolute left-full top-0 hidden group-hover/sub:block bg-white shadow-xl rounded-md py-2 w-64 border border-gray-100">
                                <li class="px-4 py-2 hover:bg-amber-50">Bangla MCQ</li>
                                <li class="px-4 py-2 hover:bg-amber-50">Bangla Notes</li>
                            </ul>
                        </li>
                        <li
                            class="group/sub relative px-4 py-2 hover:bg-amber-50 hover:text-amber-600 flex justify-between items-center">
                            English
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                            <ul
                                class="absolute left-full top-0 hidden group-hover/sub:block bg-white shadow-xl rounded-md py-2 w-64 border border-gray-100">
                                <li class="px-4 py-2 hover:bg-amber-50">English MCQ</li>
                                <li class="px-4 py-2 hover:bg-amber-50">English Notes</li>
                            </ul>
                        </li>
                        <li
                            class="group/sub relative px-4 py-2 hover:bg-amber-50 hover:text-amber-600 flex justify-between items-center">
                            Math
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                            <ul
                                class="absolute left-full top-0 hidden group-hover/sub:block bg-white shadow-xl rounded-md py-2 w-64 border border-gray-100">
                                <li class="px-4 py-2 hover:bg-amber-50">Math MCQ</li>
                                <li class="px-4 py-2 hover:bg-amber-50">Math Notes</li>
                            </ul>
                        </li>
                        <li
                            class="group/sub relative px-4 py-2 hover:bg-amber-50 hover:text-amber-600 flex justify-between items-center">
                            History
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                            <ul
                                class="absolute left-full top-0 hidden group-hover/sub:block bg-white shadow-xl rounded-md py-2 w-64 border border-gray-100">
                                <li class="px-4 py-2 hover:bg-amber-50">History MCQ</li>
                                <li class="px-4 py-2 hover:bg-amber-50">History Notes</li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li class="hover:text-amber-600 cursor-pointer transition">
                    <a href="#about">About Us</a>
                </li>
                <li class="hover:text-amber-600 cursor-pointer transition">FAQ</li>
                <li class="hover:text-amber-600 cursor-pointer transition capitalize">Contact</li>
            </ul>

            <div class="relative inline-block text-left font-google">
                <button id="login-btn" class="flex items-center gap-2 text-black transition-all duration-300">
                    <div
                        class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center overflow-hidden border-2 border-amber-200 shadow-sm">

                        <?php
                        $fullImagePath = $_SESSION['profile_image'] ?? null;
                        ?>

                        <?php if (!empty($fullImagePath) && file_exists($fullImagePath)): ?>
                            <img src="<?= htmlspecialchars($fullImagePath) ?>" alt="Profile"
                                class="w-full h-full object-cover">
                        <?php elseif (!empty($userName)): ?>
                            <span class="text-white font-bold text-lg uppercase">
                                <?= mb_substr($userName, 0, 1, 'UTF-8') ?>
                            </span>
                        <?php else: ?>
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                </path>
                            </svg>
                        <?php endif; ?>

                    </div>



                    <span class="font-semibold cursor-pointer">
                        <?php echo isset($_SESSION['user_id']) ? htmlspecialchars($_SESSION['user_name']) : 'Login'; ?>
                    </span>
                </button>

                <div id="login-menu"
                    class="absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-2xl border border-gray-100 z-50 invisible opacity-0 translate-y-[-10px] transition-all duration-300 ease-out">
                    <div class="p-2">
                        <?php if (!isset($_SESSION['user_id'])): ?>
                            <a href="login.php"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-600 rounded-lg transition">Login</a>
                            <a href="register.php"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-600 rounded-lg transition">Registration</a>
                        <?php else: ?>
                            <a href="settings.php"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-600 rounded-lg transition">Setting</a>
                            <hr class="my-1 border-gray-100">
                            <a href="logout.php"
                                class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition font-medium">Logout</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>

        <div id="sidebar-overlay"
            class="fixed inset-0 bg-black/50 z-[60] hidden opacity-0 transition-opacity duration-300"></div>

        <div id="mobile-sidebar"
            class="fixed top-0 left-0 h-full w-72 bg-white z-[70] shadow-2xl transform -translate-x-full transition-transform duration-300 ease-in-out p-6 overflow-y-auto font-google">

            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl font-bold text-amber-600">Menu</h2>
                <button id="close-sidebar" class="text-gray-500 hover:text-red-500 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <ul class="space-y-2 text-gray-700 font-medium">
                <li>
                    <a href="index.php" class="block py-2 hover:text-amber-600 transition">Home</a>
                </li>

                <li class="border-b border-gray-50 pb-1">
                    <button
                        class="mobile-dropdown-btn flex items-center justify-between w-full py-2 hover:text-amber-600 transition focus:outline-none">
                        <span>General Knowledge</span>
                        <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <ul class="mobile-sub-menu hidden mt-1 ml-4 space-y-1 border-l-2 border-amber-100">
                        <li><a href="#"
                                class="block px-4 py-2 text-sm text-gray-600 hover:bg-amber-50 rounded-r-lg">General
                                Knowledge MCQ</a></li>
                        <li><a href="#"
                                class="block px-4 py-2 text-sm text-gray-600 hover:bg-amber-50 rounded-r-lg">General
                                Knowledge Notes</a></li>
                    </ul>
                </li>

                <li class="border-b border-gray-50 pb-1">
                    <button
                        class="mobile-dropdown-btn flex items-center justify-between w-full py-2 hover:text-amber-600 transition focus:outline-none">
                        <span>Bangla</span>
                        <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <ul class="mobile-sub-menu hidden mt-1 ml-4 space-y-1 border-l-2 border-amber-100">
                        <li><a href="#"
                                class="block px-4 py-2 text-sm text-gray-600 hover:bg-amber-50 rounded-r-lg">Bangla
                                MCQ</a></li>
                        <li><a href="#"
                                class="block px-4 py-2 text-sm text-gray-600 hover:bg-amber-50 rounded-r-lg">Bangla
                                Notes</a></li>
                    </ul>
                </li>
                <li class="border-b border-gray-50 pb-1">
                    <button
                        class="mobile-dropdown-btn flex items-center justify-between w-full py-2 hover:text-amber-600 transition focus:outline-none">
                        <span>English</span>
                        <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <ul class="mobile-sub-menu hidden mt-1 ml-4 space-y-1 border-l-2 border-amber-100">
                        <li><a href="#"
                                class="block px-4 py-2 text-sm text-gray-600 hover:bg-amber-50 rounded-r-lg">English
                                MCQ</a></li>
                        <li><a href="#"
                                class="block px-4 py-2 text-sm text-gray-600 hover:bg-amber-50 rounded-r-lg">English
                                Notes</a></li>
                    </ul>
                </li>
                <li class="border-b border-gray-50 pb-1">
                    <button
                        class="mobile-dropdown-btn flex items-center justify-between w-full py-2 hover:text-amber-600 transition focus:outline-none">
                        <span>Math</span>
                        <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <ul class="mobile-sub-menu hidden mt-1 ml-4 space-y-1 border-l-2 border-amber-100">
                        <li><a href="#"
                                class="block px-4 py-2 text-sm text-gray-600 hover:bg-amber-50 rounded-r-lg">Math
                                MCQ</a></li>
                        <li><a href="#"
                                class="block px-4 py-2 text-sm text-gray-600 hover:bg-amber-50 rounded-r-lg">Math
                                Notes</a></li>
                    </ul>
                </li>
                <li class="border-b border-gray-50 pb-1">
                    <button
                        class="mobile-dropdown-btn flex items-center justify-between w-full py-2 hover:text-amber-600 transition focus:outline-none">
                        <span>History</span>
                        <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <ul class="mobile-sub-menu hidden mt-1 ml-4 space-y-1 border-l-2 border-amber-100">
                        <li><a href="#"
                                class="block px-4 py-2 text-sm text-gray-600 hover:bg-amber-50 rounded-r-lg">History
                                MCQ</a></li>
                        <li><a href="#"
                                class="block px-4 py-2 text-sm text-gray-600 hover:bg-amber-50 rounded-r-lg">History
                                Notes</a></li>
                    </ul>
                </li>

                <a href="#about" class="sidebar-link block py-2 hover:text-amber-600 transition">About</a>
                <li><a href="#" class="block py-2 hover:text-amber-600 transition">FAQ</a></li>
                <li><a href="#" class="block py-2 hover:text-amber-600 transition">Contact</a></li>
            </ul>
        </div>
    </section>

    <section class="max-w-6xl mx-auto">
        <div class="flex flex-col sm:flex-row h-auto sm:h-[300px] overflow-hidden hero-background w-full">

            <div class="flex-1 flex flex-col items-center justify-center p-4">
                <div class="flex flex-row items-center justify-center gap-4">
                    <img src="/main/img/globe.gif" alt="globe" class="w-16 h-16 sm:w-32 sm:h-32 object-contain">
                    <h1 class="text-lg sm:text-2xl font-bold text-white font-tiro">প্রশ্নমালা</h1>
                    <img src="/main/img/brain.png" alt="brain" class="w-16 h-16 sm:w-32 sm:h-32 object-contain">
                </div>
                <div class="mt-2 text-center">
                    <h1 class="text-lg sm:text-xl px-10 sm:px-4 font-medium text-white font-tiro">নিজেকে যাছাই করুন,
                        তৈরি
                        করুন,
                        চমকে
                        দিন !</h1>
                </div>
            </div>

            <div class="hidden sm:flex items-center">
                <img src="/main/img/line.png" alt="" class="h-4/5 w-auto">
            </div>

            <div class="hidden sm:flex flex-1 items-center justify-center p-2 bg-gray-50 sm:bg-transparent">
                <div class="swiper mySwiper w-full max-w-xl mx-auto">
                    <div class="swiper-wrapper pb-1">

                        <div class="swiper-slide flex items-center justify-center">
                            <div class="w-72 h-72 flex items-center justify-center">
                                <img src="/main/img/bangladesh book.png" alt="Bangladesh Book"
                                    class="max-w-full max-h-full object-contain">
                            </div>
                        </div>

                        <div class="swiper-slide flex items-center justify-center">
                            <div class="w-72 h-72 flex items-center justify-center">
                                <img src="/main/img/history image.png" alt="History"
                                    class="max-w-full max-h-full object-contain">
                            </div>
                        </div>

                        <div class="swiper-slide flex items-center justify-center">
                            <div class="w-72 h-72 flex items-center justify-center">
                                <img src="/main/img/science book.png" alt="Science Book"
                                    class="max-w-full max-h-full object-contain">
                            </div>
                        </div>

                    </div>

                    <div class="swiper-pagination text-amber-500"></div>
                </div>
            </div>

        </div>
    </section>

    <section class="max-w-6xl mx-auto p-8 bg-white h-[500px] sm:h-[400px]">
        <div class="flex flex-col items-center font-tiro">
            <h1 class="text-2xl font-semibold mb-8 text-amber-600 border-b-2 border-amber-500 pb-2">
                আজকের প্রশ্নমালা
            </h1>

            <div class="w-full max-w-2xl bg-white p-1 sm:p-6">

                <?php if ($isFinished): ?>
                    <div class="text-center py-10 animate-fade-up">
                        <h1 class="text-xl font-medium text-amber-600 mb-4">অভিনন্দন! আপনি সবগুলো ধাপ সম্পন্ন করেছেন !! 🏆
                        </h1>
                        <p class="text-2xl text-gray-700 mb-6">আপনার চূড়ান্ত স্কোর:
                            <span class="font-bold text-green-600"><?php echo $_SESSION['score'] ?? 0; ?></span>
                        </p>

                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="index.php?reset=1"
                                class="inline-block py-3 px-10 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition shadow-lg">
                                আবার শুরু করুন
                            </a>

                            <button onclick="showAnswers()"
                                class="inline-block py-3 px-10 bg-gray-800 text-white font-bold rounded-xl hover:bg-gray-900 transition shadow-lg cursor-pointer">
                                উত্তর দেখুন
                            </button>
                        </div>
                    </div>


                <?php elseif ($showLoginNotice): ?>
                    <div class="text-center py-10">
                        <h2 class="text-xl font-bold text-amber-600 mb-2">চমৎকার! আপনি ১ম রাউন্ড শেষ করেছেন।</h2>
                        <p class="text-lg text-gray-700 mb-4">বর্তমান স্কোর: <?php echo $_SESSION['score']; ?></p>
                        <div class="bg-amber-50 p-6 rounded-2xl border border-amber-200">
                            <p class="text-gray-800 mb-4">২য় রাউন্ড খেলতে লগইন করুন।</p>
                            <a href="login.php?redirect_to_quiz=round2"
                                class="py-2 px-8 bg-blue-600 text-white font-bold rounded-lg inline-block">লগইন করুন</a>
                        </div>
                    </div>

                <?php elseif ($question): ?>
                    <form action="process.php" method="POST">
                        <div class="animate-fade-right">
                            <p class="text-lg font-semibold mb-4 text-gray-800 leading-relaxed">
                                <?php echo $question['question_text']; ?>
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <label class="flex items-center gap-4 p-2 cursor-pointer transition-all group">
                                    <input type="radio" name="answer" value="a" class="peer hidden" required>
                                    <div
                                        class="w-10 h-10 flex-shrink-0 rounded-full border-2 border-gray-300 flex items-center justify-center text-lg font-bold transition-all peer-checked:bg-amber-500 peer-checked:border-amber-500 peer-checked:text-white group-hover:border-amber-400">
                                        ক
                                    </div>
                                    <span class="text-gray-700 font-medium text-lg">
                                        <?php echo $question['option_a']; ?>
                                    </span>
                                </label>

                                <label class="flex items-center gap-4 p-2 cursor-pointer group">
                                    <input type="radio" name="answer" value="b" class="peer hidden">
                                    <div
                                        class="w-10 h-10 flex-shrink-0 rounded-full border-2 border-gray-300 flex items-center justify-center text-lg font-bold transition-all peer-checked:bg-amber-500 peer-checked:border-amber-500 peer-checked:text-white group-hover:border-amber-400">
                                        খ
                                    </div>
                                    <span class="text-gray-700 font-medium text-lg">
                                        <?php echo $question['option_b']; ?>
                                    </span>
                                </label>

                                <label class="flex items-center gap-4 p-2 cursor-pointer group">
                                    <input type="radio" name="answer" value="c" class="peer hidden">
                                    <div
                                        class="w-10 h-10 flex-shrink-0 rounded-full border-2 border-gray-300 flex items-center justify-center text-lg font-bold transition-all peer-checked:bg-amber-500 peer-checked:border-amber-500 peer-checked:text-white group-hover:border-amber-400">
                                        গ
                                    </div>
                                    <span class="text-gray-700 font-medium text-lg">
                                        <?php echo $question['option_c']; ?>
                                    </span>
                                </label>

                                <label class="flex items-center gap-4 p-2 cursor-pointer group">
                                    <input type="radio" name="answer" value="d" class="peer hidden">
                                    <div
                                        class="w-10 h-10 flex-shrink-0 rounded-full border-2 border-gray-300 flex items-center justify-center text-lg font-bold transition-all peer-checked:bg-amber-500 peer-checked:border-amber-500 peer-checked:text-white group-hover:border-amber-400">
                                        ঘ
                                    </div>
                                    <span class="text-gray-700 font-medium text-lg">
                                        <?php echo $question['option_d']; ?>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <input type="hidden" name="question_id" value="<?php echo $question['id']; ?>">

                        <div class="flex items-center justify-between mt-4 pt-6">
                            <span class="text-gray-400 text-sm italic">ধাপ <?php echo $currentStep; ?> / 20</span>
                            <button id="nextBtn" type="submit" disabled
                                class="py-2 px-4 border-2 border-amber-500 text-amber-600 font-semibold text-lg rounded-xl enabled:bg-amber-500 enabled:text-white disabled:opacity-50">
                                <?php
                                if ($currentStep == 10 && !isset($_SESSION['user_id'])) {
                                    echo "স্কোর দেখুন ও ২য় রাউন্ড";
                                } elseif ($currentStep == 20) {
                                    echo "ফলাফল দেখুন";
                                } else {
                                    echo "পরবর্তী প্রশ্ন";
                                }
                                ?>
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="about" class="max-w-6xl mx-auto py-12 md:py-20  bg-white ">
        <div class="flex flex-col lg:flex-row items-center p-4 gap-10 lg:gap-16">

            <div class="w-full lg:w-1/2 order-2 lg:order-1">
                <div class="relative max-w-md mx-auto lg:max-w-full">

                    <div
                        class="absolute -inset-2 bg-gradient-to-tr from-amber-400 to-orange-500 rounded-3xl blur-xl opacity-20 transition duration-1000 group-hover:opacity-40">
                    </div>

                    <div class="relative bg-white border border-gray-100 p-4 md:p-8 rounded-3xl shadow-2xl">
                        <div class="swiper mySwiper w-full h-auto">
                            <div class="swiper-wrapper pb-10">
                                <div class="swiper-slide flex items-center justify-center">
                                    <div class="w-60 h-60 md:w-80 md:h-80 flex items-center justify-center">
                                        <img src="/main/img/bangladesh book.png" alt="Bangladesh Book"
                                            class="max-w-full max-h-full object-contain drop-shadow-lg">
                                    </div>
                                </div>
                                <div class="swiper-slide flex items-center justify-center">
                                    <div class="w-60 h-60 md:w-80 md:h-80 flex items-center justify-center">
                                        <img src="/main/img/history image.png" alt="History"
                                            class="max-w-full max-h-full object-contain drop-shadow-lg">
                                    </div>
                                </div>
                                <div class="swiper-slide flex items-center justify-center">
                                    <div class="w-60 h-60 md:w-80 md:h-80 flex items-center justify-center">
                                        <img src="/main/img/science book.png" alt="Science Book"
                                            class="max-w-full max-h-full object-contain drop-shadow-lg">
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>

                    <div
                        class="absolute top-4 -right-4 md:-right-8 bg-white px-5 py-3 rounded-2xl shadow-xl border-l-4 border-amber-500 animate-bounce hidden sm:block">
                        <span class="text-sm md:text-base font-bold text-gray-800 flex items-center gap-2">
                            ৫০০+ কুইজ প্রশ্ন <span class="text-xl">📚</span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-1/2 order-1 lg:order-2 text-center lg:text-left font-google">
                <div class="inline-block px-4 py-1.5 mb-4 bg-amber-50 rounded-full border border-amber-100">
                    <span class="text-black text-xs md:text-sm font-bold tracking-widest uppercase">আমাদের
                        সম্পর্কে</span>
                </div>

                <h2 class="text-2xl md:text-4xl lg:text-5xl font-extrabold text-gray-900 mb-6 leading-[1.2]">
                    আপনার মেধা-বিকাশ যাচাই করার <br class="hidden lg:block">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-orange-600">সেরা
                        মাধ্যম</span>
                </h2>

                <p class="text-gray-600 mb-8 leading-relaxed text-base md:text-lg lg:max-w-xl">
                    আমাদের কুইজ ওয়েবসাইটটি তৈরি করা হয়েছে শিক্ষার্থীদের সাধারণ জ্ঞান এবং মেধা বিকাশের জন্য। এখানে আপনি
                    পাবেন ইতিহাস, বিজ্ঞান, এবং সাধারণ জ্ঞানসহ নানা বিষয়ের ওপর হাজারো প্রশ্ন।
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-10">
                    <div class="flex items-center lg:items-start gap-4 mx-auto lg:mx-0">
                        <div class="flex-shrink-0 bg-amber-500 text-white p-2.5 rounded-xl shadow-lg shadow-amber-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                </path>
                            </svg>
                        </div>
                        <div class="text-left">
                            <h5 class="text-gray-900 font-bold">সহজ নেভিগেশন</h5>
                            <p class="text-gray-500 text-sm">সহজেই কুইজ খুঁজে নিন।</p>
                        </div>
                    </div>

                    <div class="flex items-center lg:items-start gap-4 mx-auto lg:mx-0">
                        <div
                            class="flex-shrink-0 bg-orange-500 text-white p-2.5 rounded-xl shadow-lg shadow-orange-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div class="text-left">
                            <h5 class="text-gray-900 font-bold">তাৎক্ষণিক ফলাফল</h5>
                            <p class="text-gray-500 text-sm">তাৎক্ষণিক স্কোর দেখুন।</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <button
                        class="px-10 py-4 bg-white hover:bg-amber-00 text-black font-bold rounded-xl border border-gray-200 transition-all ">
                        শুরু করুন
                    </button>
                    <button
                        class="px-10 py-4 bg-white hover:bg-gray-50 text-gray-700 font-bold rounded-xl border border-gray-200 transition-all duration-300">
                        ভিডিও দেখুন
                    </button>
                </div>
            </div>

        </div>

    </section>

</body>

</html>


<script src="/main/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

    var swiper = new Swiper(".mySwiper", {
        loop: true,
        spaceBetween: 30,
        centeredSlides: true,
        speed: 1000,
        autoplay: {
            delay: 6000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },

        keyboard: {
            enabled: true,
        },
    });
    // next question button
    const answers = document.querySelectorAll('input[name="answer"]');
    const nextBtn = document.getElementById('nextBtn');

    answers.forEach(answer => {
        answer.addEventListener('change', () => {
            nextBtn.disabled = false;
            nextBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            nextBtn.classList.add('cursor-pointer');
        });
    });
    // all question answer show
    function showAnswers() {
        const questions = <?php echo json_encode($_SESSION['quiz_questions_data'] ?? []); ?>;
        const userAnswers = <?php echo json_encode($_SESSION['user_answers'] ?? []); ?>;

        let content = '<div class="text-left space-y-4 max-h-[60vh] overflow-y-auto pr-2">';

        questions.forEach((q, index) => {
            const uAns = userAnswers[q.id];
            const cAns = q.correct_option;
            const isCorrect = (uAns === cAns);

            content += `
            <div class="border-b pb-3 p-2 ${isCorrect ? 'bg-green-50' : 'bg-red-50'} rounded-lg font-google">
                <p class="font-bold text-gray-800">${index + 1}. ${q.question_text}</p>
                
                <p class="text-sm mt-1">
                    <span class="font-semibold text-gray-600">আপনার উত্তর:</span> 
                    <span class="${isCorrect ? 'text-green-600' : 'text-red-600'} font-bold">
                        ${uAns ? q['option_' + uAns] : 'উত্তর দেননি'}
                    </span>
                    ${isCorrect ? ' ✅' : ' ❌'}
                </p>

                ${!isCorrect ? `
                <p class="text-sm text-green-700 mt-1">
                    <span class="font-semibold">সঠিক উত্তর:</span> ${q['option_' + cAns]}
                </p>` : ''}
            </div>
        `;
        });

        content += '</div>';

        Swal.fire({
            title: 'ফলাফল বিশ্লেষণ',
            html: content,
            width: '600px',
            confirmButtonColor: '#f59e0b',
            confirmButtonText: 'ঠিক আছে'
        });
    }
</script>
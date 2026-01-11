<?php
session_start();
require_once 'db.php';

// ১. কুইজ শেষ হয়েছে কি না চেক করা
$isFinished = (isset($_GET['status']) && $_GET['status'] === 'finished');

// ২. রিসেট লজিক
if (isset($_GET['reset']) || !isset($_SESSION['quiz_step'])) {
    $_SESSION['quiz_step'] = 1;
    $_SESSION['score'] = 0;
    if (isset($_GET['reset'])) {
        header("Location: index.php");
        exit;
    }
}

// ৩. বর্তমান ধাপ নির্ধারণ
$currentStep = $_SESSION['quiz_step'];

// ৪. প্রশ্ন লোড করার লজিক (যদি কুইজ শেষ না হয়)
$question = null;
$showLoginNotice = false;

if (!$isFinished) {
    if ($currentStep > 10 && !isset($_SESSION['user_id'])) {
        $showLoginNotice = true;
    } else {
        $questionId = isset($_GET['id']) ? (int) $_GET['id'] : null;

        // প্রথমবার আসলে প্রথম প্রশ্নটি খুঁজে বের করা
        if (!$questionId) {
            $firstQ = $pdo->query("SELECT id FROM questions ORDER BY id ASC LIMIT 1")->fetch();
            $questionId = $firstQ ? $firstQ['id'] : 0;
        }

        $stmt = $pdo->prepare("SELECT * FROM questions WHERE id = ?");
        $stmt->execute([$questionId]);
        $question = $stmt->fetch();

        // যদি ২০ ধাপের আগে প্রশ্ন শেষ হয়ে যায়, তবে শেষ হিসেবে গণ্য হবে
        if (!$question && $currentStep > 1) {
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
    <section class="max-w-6xl mx-auto top-navbar">
        <nav
            class="bg-white py-2 px-6 shadow-md flex items-center justify-between font-google sticky-nav sticky top-0 z-50">

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
                        <a href="index.php">ALL QUIZ</a>
                    </h1>
                </div>
            </div>

            <ul class="hidden lg:flex items-center gap-8 text-gray-600 font-medium">
                <li class="hover:text-amber-600 cursor-pointer transition">Home</li>

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

                <li class="hover:text-amber-600 cursor-pointer transition">About</li>
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

                <li><a href="#" class="block py-2 hover:text-amber-600 transition">About</a></li>
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
                    <h1 class="text-lg sm:text-2xl font-bold text-white font-tiro">অল কুইজ</h1>
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

            <div
                class="hidden sm:flex flex-1 flex items-center justify-center p-2 bg-gray-50 sm:bg-transparent font-tiro">
                <div class="swiper mySwiper w-full max-w-xl mx-auto">
                    <div class="swiper-wrapper pb-4">

                        <div class="swiper-slide p-6">
                            <div class="flex flex-col ">
                                <p class="text-lg font-bold mb-6 text-gray-800">
                                    প্রশ্ন : 'আমার দেখা রাজনীতির পঞ্চাশ বছর' গ্রন্থটির রচয়িতা কে?
                                </p>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="radio" name="question1" class="peer hidden">
                                        <div class="w-8 h-8 rounded-full border-2 border-gray-300 flex items-center justify-center text-md font-medium transition-all 
                        peer-checked:bg-amber-500 peer-checked:border-amber-500 peer-checked:text-white">
                                            ক</div>
                                        <span class="text-gray-700 font-medium">আবুল মনসুর আহমদ</span>
                                    </label>
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <div class="relative flex items-center justify-center">
                                            <input type="radio" name="question1" class="peer hidden">
                                            <div class="w-8 h-8 rounded-full border-2 border-gray-300 flex items-center justify-center text-md font-medium transition-all 
                        peer-checked:bg-amber-500 peer-checked:border-amber-500 peer-checked:text-white">
                                                খ
                                            </div>
                                        </div>
                                        <span class="text-md font-medium text-gray-700">ড. মোহাম্মদ শহীদুল্লাহ</span>
                                    </label>
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <div class="relative flex items-center justify-center">
                                            <input type="radio" name="question1" class="peer hidden">
                                            <div class="w-8 h-8 rounded-full border-2 border-gray-300 flex items-center justify-center text-md font-medium transition-all 
                        peer-checked:bg-amber-500 peer-checked:border-amber-500 peer-checked:text-white">
                                                গ
                                            </div>
                                        </div>
                                        <span class="text-md font-medium text-gray-700">মুহাম্মদ আবদুল হাই</span>
                                    </label>

                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <div class="relative flex items-center justify-center">
                                            <input type="radio" name="question1" class="peer hidden">
                                            <div class="w-8 h-8 rounded-full border-2 border-gray-300 flex items-center justify-center text-md font-medium transition-all 
                        peer-checked:bg-amber-500 peer-checked:border-amber-500 peer-checked:text-white">
                                                ঘ
                                            </div>
                                        </div>
                                        <span class="text-md font-medium text-gray-700">অন্য কেউ</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="swiper-slide p-6">
                            <div class="flex flex-col">
                                <p class="text-lg font-bold mb-6 text-gray-800">
                                    প্রশ্ন : আন্তর্জাতিক আদালতের একজন বিচারক কয় বছরের জন্য নির্বাচিত হন?
                                </p>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="radio" name="question2" class="peer hidden">
                                        <div class="w-8 h-8 rounded-full border-2 border-gray-300 flex items-center justify-center text-md font-medium transition-all 
                        peer-checked:bg-amber-500 peer-checked:border-amber-500 peer-checked:text-white">
                                            ক</div>
                                        <span class="text-gray-700 font-medium">৩ বছর</span>
                                    </label>
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <div class="relative flex items-center justify-center">
                                            <input type="radio" name="question2" class="peer hidden">
                                            <div class="w-8 h-8 rounded-full border-2 border-gray-300 flex items-center justify-center text-md font-medium transition-all 
                        peer-checked:bg-amber-500 peer-checked:border-amber-500 peer-checked:text-white">
                                                খ
                                            </div>
                                        </div>
                                        <span class="text-md font-medium text-gray-700">৫ বছর</span>
                                    </label>

                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <div class="relative flex items-center justify-center">
                                            <input type="radio" name="question2" class="peer hidden">
                                            <div class="w-8 h-8 rounded-full border-2 border-gray-300 flex items-center justify-center text-md font-medium transition-all 
                        peer-checked:bg-amber-500 peer-checked:border-amber-500 peer-checked:text-white">
                                                গ
                                            </div>
                                        </div>
                                        <span class="text-md font-medium text-gray-700">৭ বছর</span>
                                    </label>

                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <div class="relative flex items-center justify-center">
                                            <input type="radio" name="question2" class="peer hidden">
                                            <div class="w-8 h-8 rounded-full border-2 border-gray-300 flex items-center justify-center text-md font-medium transition-all 
                        peer-checked:bg-amber-500 peer-checked:border-amber-500 peer-checked:text-white">
                                                ঘ
                                            </div>
                                        </div>
                                        <span class="text-md font-medium text-gray-700">৯ বছর</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="swiper-pagination text-amber-500"></div>

                </div>
            </div>

        </div>
    </section>

    <section class="max-w-6xl mx-auto p-8 bg-white shadow-xl">
        <div class="flex flex-col items-center font-tiro">
            <h1 class="text-2xl font-semibold mb-8 text-amber-600 border-b-2 border-amber-500 pb-2">
                আজকের কুইজ
            </h1>

            <div class="w-full max-w-2xl bg-white p-1 sm:p-6">

                <?php if ($isFinished): ?>
                    <div class="text-center py-10 animate-fade-up">
                        <h1 class="text-xl font-medium text-amber-600 mb-4">অভিনন্দন! আপনি সবগুলো ধাপ সম্পন্ন করেছেন !! 🏆
                        </h1>
                        <p class="text-2xl text-gray-700 mb-6">আপনার চূড়ান্ত স্কোর:
                            <span class="font-bold text-green-600"><?php echo $_SESSION['score'] ?? 0; ?></span>
                        </p>
                        <a href="index.php?reset=1"
                            class="inline-block py-3 px-10 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition shadow-lg">
                            আবার শুরু করুন
                        </a>
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
                            <p class="text-xl font-semibold mb-4 text-gray-800 leading-relaxed">
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

                        <div class="flex items-center justify-between mt-10 border-t pt-6">
                            <span class="text-gray-400 text-sm italic">ধাপ <?php echo $currentStep; ?> / 20</span>
                            <button id="nextBtn" type="submit" disabled
                                class="py-2 px-8 border-2 border-amber-500 text-amber-600 font-semibold text-lg rounded-xl enabled:bg-amber-500 enabled:text-white disabled:opacity-50">
                                <?php echo ($currentStep == 10 && !isset($_SESSION['user_id'])) ? "স্কোর দেখুন ও ২য় রাউন্ড" : "পরবর্তী প্রশ্ন"; ?>
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </section>

</body>

</html>


<script src="/main/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
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

    //submit button enable code
    const answers = document.querySelectorAll('input[name="answer"]');
    const nextBtn = document.getElementById('nextBtn');

    answers.forEach(answer => {
        answer.addEventListener('change', () => {
            nextBtn.disabled = false;
            nextBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            nextBtn.classList.add('cursor-pointer');
        });
    }); 
</script>
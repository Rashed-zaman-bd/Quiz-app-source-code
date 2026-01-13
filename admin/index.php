<?php
session_start();
include '../db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="/main/img/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../main/main.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>quiz admin</title>


</head>

<body>
    <section class="max-w-6xl mx-auto top-navbar">
        <nav
            class="bg-white py-2 px-6 shadow-md flex items-center justify-between font-google sticky-nav sticky top-0 z-50">

            <div class="flex items-center gap-4">
                <button id="mobile-menu-btn" class=" text-gray-700 focus:outline-none">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <div class="flex items-center gap-3">
                    <img src="/main/img/new logo.png" alt="Logo" class="w-10 h-10 object-contain">
                    <h1 class="text-xl font-bold tracking-tight text-gray-800">
                        <a href="index.php">Admin</a>
                    </h1>
                </div>
            </div>



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
                            <a href="../login.php"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-600 rounded-lg transition">Login</a>
                            <a href="../register.php"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-600 rounded-lg transition">Registration</a>
                        <?php else: ?>
                            <a href="settings.php"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-600 rounded-lg transition">Setting</a>
                            <hr class="my-1 border-gray-100">
                            <a href="../logout.php"
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
                    <a href="index.php" class="block py-2 hover:text-amber-600 transition">Admin</a>
                </li>

                <li class="border-b border-gray-50 pb-1">
                    <button
                        class="mobile-dropdown-btn flex items-center justify-between w-full py-2 hover:text-amber-600 transition focus:outline-none">
                        <span>Questions</span>
                        <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <ul class="mobile-sub-menu hidden mt-1 ml-4 space-y-1 border-l-2 border-amber-100">
                        <li class="border-b border-gray-50 pb-1">
                            <a href="index.php?page=add_question"
                                class="flex items-center gap-2 py-2 text-amber-600 font-bold hover:bg-amber-50 px-2 rounded-lg transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span>Add Question</span>
                            </a>
                        </li>
                        <li class="border-b border-gray-50 pb-1">
                            <a href="index.php?page=view_questions"
                                class="flex items-center gap-2 py-2 text-gray-700 hover:text-amber-600 transition px-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                                <span>Questions List</span>
                            </a>
                        </li>
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


            </ul>
        </div>
    </section>


    <section class="max-w-6xl mx-auto">
        <?php
        $page = $_GET['page'] ?? 'dashboard';

        switch ($page) {
            case 'add_question':
                include 'add_question.php';
                break;
            case 'view_questions':
                include 'view_questions.php';
                break;
            case 'edit_question':
                include 'edit_question.php';
                break;
            default:
                echo '<h2 class="text-2xl font-bold">Welcome Admin</h2>';
                break;
        }
        ?>
    </section>

</body>

</html>


<script src="/main/main.js"></script>
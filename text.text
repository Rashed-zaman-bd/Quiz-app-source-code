<?php
session_start();
require_once 'db.php';

// ১. যদি অলরেডি লগইন থাকে, তবে সরাসরি ড্যাশবোর্ডে পাঠিয়ে দেওয়া
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_role'] === 'admin') {
        header("Location: admin/index.php");
    } else {
        header("Location: index.php");
    }
    exit;
}

$error = "";

// ২. লগইন ফর্ম সাবমিট হলে
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mobile = htmlspecialchars(strip_tags(trim($_POST['mobile'])));
    $password = $_POST['password'];

    if (empty($mobile) || empty($password)) {
        $error = "সবগুলো ঘর পূরণ করুন!";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM user WHERE mobile = :mobile LIMIT 1");
            $stmt->execute(['mobile' => $mobile]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                if (password_verify($password, $user['password'])) {
                    // লগইন সফল
                    session_regenerate_id(true);

                    // সেশনে ডাটা রাখা
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['profile_image'] = $user['profile_image'];

                    // --- স্মার্ট রিডাইরেক্ট লজিক শুরু ---

                    // কুইজের Round 2 রিডাইরেক্ট চেক (URL এ যদি redirect_to_quiz থাকে)
                    if (isset($_GET['redirect_to_quiz']) && $_GET['redirect_to_quiz'] == 'round2') {
                        header("Location: index.php?id=11");
                        exit;
                    }

                    // রোল চেক করে পেজ নির্ধারণ
                    if ($user['role'] === 'admin') {
                        header("Location: admin/index.php");
                    } else {
                        header("Location: index.php");
                    }
                    exit;
                    // --- স্মার্ট রিডাইরেক্ট লজিক শেষ ---

                } else {
                    $error = "ভুল পাসওয়ার্ড! আবার চেষ্টা করুন।";
                }
            } else {
                $error = "এই মোবাইল নম্বরটি নিবন্ধিত নয়!";
            }
        } catch (PDOException $e) {
            error_log("Login Error: " . $e->getMessage());
            $error = "দুঃখিত, সমস্যা হয়েছে।";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg">
        <h2 class="text-2xl font-bold text-center text-amber-600 mb-6 font-sans">Login to Account</h2>

        <?php if (!empty($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-4 text-center text-sm">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4 text-center text-sm">
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 font-medium mb-1">Mobile No</label>
                <input type="text" name="mobile" required placeholder="01XXXXXXXXX"
                    class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-amber-400 focus:outline-none">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Password</label>
                <input type="password" name="password" required placeholder="******"
                    class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-amber-400 focus:outline-none">
            </div>

            <button type="submit"
                class="w-full py-3 bg-amber-500 text-white font-semibold rounded-xl hover:bg-amber-600 transition-all duration-300">
                Login
            </button>
        </form>

        <p class="text-center text-gray-600 mt-6">
            Have no account?
            <a href="register.php" class="text-amber-600 font-semibold hover:underline">Registration</a>
        </p>
    </div>
</body>

</html>
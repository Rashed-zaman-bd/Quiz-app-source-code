<?php
session_start();
// সেশন থেকে ডাটা নিয়ে আসা
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
// একবারে ব্যবহারের পর সেশন ক্লিয়ার করা
unset($_SESSION['errors'], $_SESSION['old']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .err {
            color: #dc2626;
            font-size: 0.75rem;
            margin-top: 2px;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center py-10">

    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg border">
        <h2 class="text-2xl font-bold text-center text-amber-600 mb-6">Create Account</h2>

        <form action="register_process.php" method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block text-gray-700 font-medium text-sm">Full Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                    class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-amber-400 outline-none">
                <?php if (isset($errors['name'])): ?>
                    <p class="err"><?= $errors['name'] ?></p> <?php endif; ?>
            </div>

            <div>
                <label class="block text-gray-700 font-medium text-sm">Mobile No</label>
                <input type="text" name="mobile" value="<?= htmlspecialchars($old['mobile'] ?? '') ?>"
                    class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-amber-400 outline-none">
                <?php if (isset($errors['mobile'])): ?>
                    <p class="err"><?= $errors['mobile'] ?></p> <?php endif; ?>
            </div>

            <div>
                <label class="block text-gray-700 font-medium text-sm">Email Address</label>
                <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                    class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-amber-400 outline-none">
                <?php if (isset($errors['email'])): ?>
                    <p class="err"><?= $errors['email'] ?></p> <?php endif; ?>
            </div>

            <div>
                <label class="block text-gray-700 font-medium text-sm">Password</label>
                <input type="password" name="password"
                    class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-amber-400 outline-none">
                <?php if (isset($errors['password'])): ?>
                    <p class="err"><?= $errors['password'] ?></p> <?php endif; ?>
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-gray-700 font-medium mb-1">Confirm Password</label>
                <input type="password" name="confirm_password"
                    class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-amber-400 focus:outline-none">
                <?php if (isset($errors['confirm_password'])): ?>
                    <p class="err"><?= $errors['confirm_password'] ?></p> <?php endif; ?>
            </div>

            <div>
                <label class="block text-gray-700 font-medium text-sm">Profile Image</label>
                <input type="file" name="profile_image" accept="image/*" class="w-full px-3 py-2 border rounded-xl">
                <?php if (isset($errors['profile_image'])): ?>
                    <p class="err"><?= $errors['profile_image'] ?></p> <?php endif; ?>
            </div>

            <?php if (isset($errors['general'])): ?>
                <p class="text-red-700 p-2  text-center m-2 text-sm"><?= $errors['general'] ?></p>
            <?php endif; ?>

            <button type="submit"
                class="w-full py-3 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition">Register
                Now</button>
        </form>

        <!-- Login Link -->
        <p class="text-center text-gray-600 mt-6 font-Oswald">
            Already have an account?
            <a href="login.php" class="text-amber-600 font-semibold hover:underline">
                Login
            </a>
        </p>
    </div>

</body>

</html>
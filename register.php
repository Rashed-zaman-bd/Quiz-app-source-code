<?php
session_start();
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .err {
            color: #dc2626;
            font-size: 0.75rem;
            margin-top: 2px;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white p-6 sm:p-8 rounded-2xl shadow-lg">
        <h2 class="text-2xl font-bold text-center text-amber-600 mb-6">Create Account</h2>

        <form action="register_process.php" method="POST" enctype="multipart/form-data" class="space-y-4">

            <div>
                <label class="block text-gray-700 font-medium text-sm mb-1">Full Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                    class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-amber-400 outline-none transition-all text-base">
                <?php if (isset($errors['name'])): ?>
                    <p class="err"><?= $errors['name'] ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label class="block text-gray-700 font-medium text-sm mb-1">Mobile No</label>
                <input type="text" name="mobile" value="<?= htmlspecialchars($old['mobile'] ?? '') ?>"
                    class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-amber-400 outline-none text-base">
                <?php if (isset($errors['mobile'])): ?>
                    <p class="err"><?= $errors['mobile'] ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label class="block text-gray-700 font-medium text-sm mb-1">Email Address</label>
                <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                    class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-amber-400 outline-none text-base">
                <?php if (isset($errors['email'])): ?>
                    <p class="err"><?= $errors['email'] ?></p>
                <?php endif; ?>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium text-sm mb-1">Password</label>
                    <input type="password" name="password"
                        class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-amber-400 outline-none text-base">
                    <?php if (isset($errors['password'])): ?>
                        <p class="err"><?= $errors['password'] ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium text-sm mb-1">Confirm Password</label>
                    <input type="password" name="confirm_password"
                        class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-amber-400 outline-none text-base">
                    <?php if (isset($errors['confirm_password'])): ?>
                        <p class="err"><?= $errors['confirm_password'] ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <label class="block text-gray-700 font-medium text-sm mb-1 cursor-pointer">Profile Image</label>
                <input type="file" name="profile_image" accept="image/*"
                    class="w-full px-3 py-2 border rounded-xl text-sm file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                <?php if (isset($errors['profile_image'])): ?>
                    <p class="err"><?= $errors['profile_image'] ?></p>
                <?php endif; ?>
            </div>

            <?php if (isset($errors['general'])): ?>
                <div class="bg-red-50 border border-red-200 rounded-xl p-3">
                    <p class="text-red-700 text-center text-xs sm:text-sm"><?= $errors['general'] ?></p>
                </div>
            <?php endif; ?>

            <button type="submit"
                class="w-full py-3.5 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition-colors shadow-md active:scale-[0.98] cursor-pointer">
                Register Now
            </button>
        </form>

        <p class="text-center text-gray-600 mt-6 text-sm">
            Already have an account?
            <a href="login.php" class="text-amber-600 font-semibold hover:underline cursor-pointer">
                Login
            </a>
        </p>
    </div>

    <script>
        <?php if (isset($_SESSION['success_message'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Congratulations!',
                text: 'Your account has been created successfully.',
                confirmButtonColor: '#f59e0b',
                timer: 3000,
                timerProgressBar: true
            }).then((result) => {
                if (result.isConfirmed || result.isDismissed) {
                    window.location.href = 'login.php';
                }
            });

            setTimeout(() => {
                window.location.href = 'login.php';
            }, 3500);

            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
    </script>

</body>

</html>
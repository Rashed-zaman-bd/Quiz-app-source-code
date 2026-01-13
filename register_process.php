<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];
    $old = $_POST;

    $name = htmlspecialchars(strip_tags(trim($_POST['name'])));
    $mobile = htmlspecialchars(strip_tags(trim($_POST['mobile'])));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($name)) {
        $errors['name'] = "পূর্ণ নাম লিখুন!";
    }

    if (empty($mobile)) {
        $errors['mobile'] = "মোবাইল নম্বর দিন!";
    } else {
        if (!preg_match('/^01[3-9][0-9]{8}$/', $mobile)) {
            $errors['mobile'] = "সঠিক ১১ ডিজিটের মোবাইল নম্বর দিন (যেমন: 017...)!";
        }
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "সঠিক ইমেইল দিন!";
    }

    if (strlen($password) < 6) {
        $errors['password'] = "পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে!";
    } elseif ($password !== $confirm_password) {
        $errors['confirm_password'] = "পাসওয়ার্ড মেলেনি!";
    }


    $profileImage = "uploads/users/default.png";

    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($_FILES['profile_image']['tmp_name']);

        if (!in_array($mime, $allowedTypes)) {
            $errors['profile_image'] = "JPG/PNG ফাইল প্রয়োজন!";
        } elseif ($_FILES['profile_image']['size'] > 2 * 1024 * 1024) {
            $errors['profile_image'] = "সাইজ ২MB এর নিচে হতে হবে!";
        } else {
            $uploadDir = "uploads/users/";

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileExt = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid("user_", true) . "." . $fileExt;
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $filePath)) {
                $profileImage = $filePath;

                $_SESSION['profile_image'] = $profileImage;
            } else {
                $errors['profile_image'] = "ফাইল আপলোড করতে সমস্যা হয়েছে!";
            }
        }
    }

    if (empty($errors)) {
        try {
            $checkUser = $pdo->prepare("SELECT id FROM user WHERE mobile = :mobile OR email = :email LIMIT 1");
            $checkUser->execute(['mobile' => $mobile, 'email' => $email]);

            if ($checkUser->rowCount() > 0) {
                $errors['general'] = "মোবাইল বা ইমেইল অলরেডি ব্যবহৃত হয়েছে!";
            } else {
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                $sql = "INSERT INTO user (name, mobile, email, password, profile_image) VALUES (:name, :mobile, :email, :password, :profile_image)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'name' => $name,
                    'mobile' => $mobile,
                    'email' => $email,
                    'password' => $hashed_password,
                    'profile_image' => $profileImage
                ]);

                $_SESSION['success_message'] = true;
                header("Location: register.php");
                exit();

            }
        } catch (PDOException $e) {
            $errors['general'] = "ডাটাবেস ত্রুটি!";
        }
    }

    $_SESSION['errors'] = $errors;
    $_SESSION['old'] = $old;
    header("Location: register.php");
    exit;
}
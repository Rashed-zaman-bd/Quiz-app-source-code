<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $question_id = (int) $_POST['question_id'];
    $user_answer = $_POST['answer'];

    // ১. উত্তর চেক ও স্কোর আপডেট
    $stmt = $pdo->prepare("SELECT correct_option FROM questions WHERE id = :id");
    $stmt->execute(['id' => $question_id]);
    $question = $stmt->fetch();

    if ($question && $user_answer === $question['correct_option']) {
        $_SESSION['score'] = ($_SESSION['score'] ?? 0) + 1;
    }

    // ২. ধাপ বাড়ানো
    $_SESSION['quiz_step'] = ($_SESSION['quiz_step'] ?? 1) + 1;

    // ৩. পরবর্তী প্রশ্ন খোঁজা
    $nextStmt = $pdo->prepare("SELECT id FROM questions WHERE id > :current_id ORDER BY id ASC LIMIT 1");
    $nextStmt->execute(['current_id' => $question_id]);
    $nextQuestion = $nextStmt->fetch();

    // ৪. কুইজ শেষ করার লজিক
    if ($_SESSION['quiz_step'] > 20 || !$nextQuestion) {
        header("Location: index.php?status=finished");
    } else {
        header("Location: index.php?id=" . $nextQuestion['id']);
    }
    exit;
}
<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $question_id = (int) $_POST['question_id'];
    $user_answer = $_POST['answer'];

    $stmt = $pdo->prepare("SELECT correct_option FROM questions WHERE id = :id");
    $stmt->execute(['id' => $question_id]);
    $question = $stmt->fetch();

    if ($question && $user_answer === $question['correct_option']) {
        $_SESSION['score'] = ($_SESSION['score'] ?? 0) + 1;
    }

    $_SESSION['user_answers'][$question_id] = $user_answer;

    $_SESSION['quiz_step']++;
    $nextStepIndex = $_SESSION['quiz_step'] - 1;

    if ($_SESSION['quiz_step'] > 20 || !isset($_SESSION['quiz_ids'][$nextStepIndex])) {
        header("Location: index.php?status=finished");
    } else {
        header("Location: index.php");
    }
    exit;
}
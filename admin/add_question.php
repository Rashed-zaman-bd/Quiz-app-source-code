<?php

// অ্যাডমিন চেক (নিরাপত্তার জন্য)
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}
require_once '../db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question = htmlspecialchars($_POST['question_text']);
    $opt_a = htmlspecialchars($_POST['option_a']);
    $opt_b = htmlspecialchars($_POST['option_b']);
    $opt_c = htmlspecialchars($_POST['option_c']);
    $opt_d = htmlspecialchars($_POST['option_d']);
    $correct = $_POST['correct_option'];

    try {
        $sql = "INSERT INTO questions (question_text, option_a, option_b, option_c, option_d, correct_option) 
                VALUES (:q, :a, :b, :c, :d, :correct)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'q' => $question,
            'a' => $opt_a,
            'b' => $opt_b,
            'c' => $opt_c,
            'd' => $opt_d,
            'correct' => $correct
        ]);
        $message = "<p class='text-green-600'>প্রশ্নটি সফলভাবে আপলোড হয়েছে!</p>";
    } catch (PDOException $e) {
        $message = "<p class='text-red-600'>ভুল হয়েছে: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Question</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="">
    <div class="max-w-6xl mx-auto bg-white p-2 sm:p-8 pt-4 rounded-xl shadow">
        <h2 class="text-xl font-bold mb-6">নতুন প্রশ্ন যোগ করুন</h2>

        <?php echo $message; ?>

        <form action="" method="POST" class="space-y-4">
            <div>
                <label class="block font-medium">প্রশ্ন (Question Text):</label>
                <textarea name="question_text" required class="w-full border p-2 rounded"></textarea>
            </div>
            <div class="grid grid-col-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block">অপশন A:</label>
                    <input type="text" name="option_a" required class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block">অপশন B:</label>
                    <input type="text" name="option_b" required class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block">অপশন C:</label>
                    <input type="text" name="option_c" required class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block">অপশন D:</label>
                    <input type="text" name="option_d" required class="w-full border p-2 rounded">
                </div>
            </div>
            <div>
                <label class="block font-bold">সঠিক উত্তর (Correct Option):</label>
                <select name="correct_option" class="w-full border p-2 rounded">
                    <option value="a">Option A</option>
                    <option value="b">Option B</option>
                    <option value="c">Option C</option>
                    <option value="d">Option D</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">আপলোড করুন</button>
        </form>
    </div>
</body>

</html>
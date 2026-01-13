<?php
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    exit('Unauthorized Access');
}

$id = $_GET['id'] ?? null;
$message = "";

if (!$id) {
    echo "<script>window.location.href='index.php?page=view_questions';</script>";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM questions WHERE id = ?");
$stmt->execute([$id]);
$question = $stmt->fetch();

if (!$question) {
    echo "<div class='text-red-500'>প্রশ্নটি পাওয়া যায়নি!</div>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $q_text = htmlspecialchars($_POST['question_text']);
    $a = htmlspecialchars($_POST['option_a']);
    $b = htmlspecialchars($_POST['option_b']);
    $c = htmlspecialchars($_POST['option_c']);
    $d = htmlspecialchars($_POST['option_d']);
    $correct = $_POST['correct_option'];

    try {
        $updateStmt = $pdo->prepare("UPDATE questions SET question_text=?, option_a=?, option_b=?, option_c=?, option_d=?, correct_option=? WHERE id=?");
        $updateStmt->execute([$q_text, $a, $b, $c, $d, $correct, $id]);

        echo "<script>alert('সফলভাবে আপডেট করা হয়েছে!'); window.location.href='index.php?page=view_questions';</script>";
    } catch (PDOException $e) {
        $message = "<p class='text-red-600'>ভুল হয়েছে: " . $e->getMessage() . "</p>";
    }
}
?>

<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Edit Question</h2>
        <a href="index.php?page=view_questions" class="text-sm text-gray-500 hover:text-amber-600">Back to List</a>
    </div>

    <?php echo $message; ?>

    <form action="" method="POST" class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Question Text</label>
            <textarea name="question_text" required
                class="w-full border border-gray-200 p-3 rounded-xl focus:ring-2 focus:ring-amber-400 focus:outline-none"><?= htmlspecialchars($question['question_text']) ?></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Option A</label>
                <input type="text" name="option_a" value="<?= htmlspecialchars($question['option_a']) ?>" required
                    class="w-full border border-gray-200 px-4 py-2 rounded-xl focus:ring-2 focus:ring-amber-400 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Option B</label>
                <input type="text" name="option_b" value="<?= htmlspecialchars($question['option_b']) ?>" required
                    class="w-full border border-gray-200 px-4 py-2 rounded-xl focus:ring-2 focus:ring-amber-400 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Option C</label>
                <input type="text" name="option_c" value="<?= htmlspecialchars($question['option_c']) ?>" required
                    class="w-full border border-gray-200 px-4 py-2 rounded-xl focus:ring-2 focus:ring-amber-400 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Option D</label>
                <input type="text" name="option_d" value="<?= htmlspecialchars($question['option_d']) ?>" required
                    class="w-full border border-gray-200 px-4 py-2 rounded-xl focus:ring-2 focus:ring-amber-400 focus:outline-none">
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-1">Correct Option</label>
            <select name="correct_option"
                class="w-full border border-gray-200 p-3 rounded-xl focus:ring-2 focus:ring-amber-400">
                <option value="a" <?= $question['correct_option'] == 'a' ? 'selected' : '' ?>>Option A</option>
                <option value="b" <?= $question['correct_option'] == 'b' ? 'selected' : '' ?>>Option B</option>
                <option value="c" <?= $question['correct_option'] == 'c' ? 'selected' : '' ?>>Option C</option>
                <option value="d" <?= $question['correct_option'] == 'd' ? 'selected' : '' ?>>Option D</option>
            </select>
        </div>

        <div class="flex gap-3">
            <button type="submit"
                class="flex-1 py-3 bg-amber-500 text-white font-semibold rounded-xl hover:bg-amber-600 transition shadow-md">
                Update Question
            </button>
            <a href="index.php?page=view_questions"
                class="px-6 py-3 bg-gray-100 text-gray-600 font-semibold rounded-xl hover:bg-gray-200 transition text-center">
                Cancel
            </a>
        </div>
    </form>
</div>
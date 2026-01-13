<?php
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    exit('Unauthorized Access');
}

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM questions WHERE id = ?");
    if ($stmt->execute([$id])) {
        echo "<script>alert('প্রশ্নটি সফলভাবে মুছে ফেলা হয়েছে!'); window.location.href='index.php?page=view_questions';</script>";
    }
}

$stmt = $pdo->query("SELECT * FROM questions ORDER BY id DESC");
$questions = $stmt->fetchAll();
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

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        .dataTables_wrapper .dataTables_length select {
            padding: 5px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .dataTables_wrapper .dataTables_filter input {
            padding: 6px 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
        }

        table.dataTable thead th {
            border-bottom: 1px solid #eee !important;
        }

        table.dataTable td {
            border-bottom: 1px solid #f9f9f9 !important;
        }
    </style>

</head>


<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
    <div class="flex flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-xl font-bold text-gray-800">All Questions</h2>
        <a href="index.php?page=add_question"
            class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Question
        </a>
    </div>

    <div class="overflow-x-auto pb-6">
        <table id="questionTable" class="w-full text-left display">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="p-1 text-sm font-semibold text-gray-600">ID</th>
                    <th class="p-1 text-sm font-semibold text-gray-600">Question</th>
                    <th class="p-1 text-sm font-semibold text-gray-600">Options (Correct)</th>
                    <th class="p-1 text-sm font-semibold text-gray-600">Date</th>
                    <th class="p-1 text-sm font-semibold text-gray-600 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="">
                <?php $serial = 1; // Initialize counter ?>
                <?php foreach ($questions as $q): ?>
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="text-sm text-gray-500"><?= $serial++ ?></td>
                        <td class=" text-sm font-medium text-gray-800 w-1/3">
                            <?= htmlspecialchars($q['question_text']) ?>
                        </td>
                        <td class="p-1 text-sm text-gray-600">
                            <ul class="text-xs space-y-1">
                                <li class="<?= $q['correct_option'] == 'a' ? 'text-green-600 font-bold' : '' ?>">A:
                                    <?= htmlspecialchars($q['option_a']) ?>
                                </li>
                                <li class="<?= $q['correct_option'] == 'b' ? 'text-green-600 font-bold' : '' ?>">B:
                                    <?= htmlspecialchars($q['option_b']) ?>
                                </li>
                                <li class="<?= $q['correct_option'] == 'c' ? 'text-green-600 font-bold' : '' ?>">C:
                                    <?= htmlspecialchars($q['option_c']) ?>
                                </li>
                                <li class="<?= $q['correct_option'] == 'd' ? 'text-green-600 font-bold' : '' ?>">D:
                                    <?= htmlspecialchars($q['option_d']) ?>
                                </li>
                            </ul>
                        </td>
                        <td class="p-1 text-center">
                            <?= htmlspecialchars($q['created_at']) ?>
                        </td>
                        <td class="p-1 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="index.php?page=edit_question&id=<?= $q['id'] ?>"
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </a>
                                <a href="index.php?page=view_questions&delete_id=<?= $q['id'] ?>"
                                    onclick="return confirm('আপনি কি নিশ্চিত যে এই প্রশ্নটি ডিলিট করতে চান?')"
                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </a>
                            </div>
                            <div class=""></div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        $('#questionTable').DataTable({
            pageLength: 10,
            lengthMenu: [[10, 20, 50, 100], [10, 20, 50, 100, "All"]],
            language: {
                search: "Search Questions:",
                lengthMenu: "Show _MENU_ entries",
            },
            columnDefs: [
                {
                    targets: 0, // ID column
                    render: function (data, type, row) {
                        if (type === 'sort' || type === 'type') {
                            return parseInt(data.replace('#', ''), 10);
                        }
                        return data;
                    }
                }
            ]
        });
    });

</script>
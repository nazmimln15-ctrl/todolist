<?php
// toggle.php
require_once 'config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $status = ($_POST['status'] === 'done') ? 'done' : 'pending';
    if ($id > 0) {
        $stmt = mysqli_prepare($conn, "UPDATE tasks SET status = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "si", $status, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}
header('Location: index.php');
exit;

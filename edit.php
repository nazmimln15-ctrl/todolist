<?php
require_once 'config.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: index.php'); exit; }

// proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $desc  = trim($_POST['description'] ?? '');
    $status = in_array($_POST['status'] ?? '', ['pending','done']) ? $_POST['status'] : 'pending';
    if ($title !== '') {
        $stmt = mysqli_prepare($conn, "UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "sssi", $title, $desc, $status, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    header('Location: index.php');
    exit;
}

// ambil data task
$stmt = mysqli_prepare($conn, "SELECT id, title, description, status FROM tasks WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$task = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);
if (!$task) { header('Location: index.php'); exit; }
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Edit Task</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    body{font-family:Arial, sans-serif;background:#f4f7f9;margin:0;padding:24px}
    .card{max-width:700px;margin:0 auto;background:#fff;padding:16px;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,.06)}
    input, textarea, select{width:100%;padding:8px;margin-top:8px;border:1px solid #e6e9ef;border-radius:6px}
    button{margin-top:12px;padding:8px 12px;border-radius:6px;background:#2b8cff;color:#fff;border:none}
    a.back{display:inline-block;margin-top:8px;color:#374151;text-decoration:none}
  </style>
</head>
<body>
  <div class="card">
    <h2>Edit Task</h2>
    <form method="post" action="edit.php?id=<?=urlencode($task['id'])?>">
      <label>Judul</label>
      <input type="text" name="title" value="<?=htmlspecialchars($task['title'])?>" required>
      <label>Deskripsi</label>
      <textarea name="description"><?=htmlspecialchars($task['description'])?></textarea>
      <label>Status</label>
      <select name="status">
        <option value="pending" <?= $task['status']==='pending' ? 'selected':'' ?>>Pending</option>
        <option value="done" <?= $task['status']==='done' ? 'selected':'' ?>>Done</option>
      </select>
      <button type="submit">Simpan</button>
    </form>
    <a class="back" href="index.php">← Kembali</a>
  </div>
</body>
</html>

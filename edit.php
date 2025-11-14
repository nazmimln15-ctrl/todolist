<?php
// edit.php
require_once 'config.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: index.php'); exit; }

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
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="card edit">
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

  <script src="assets/js/main.js" defer></script>
</body>
</html>

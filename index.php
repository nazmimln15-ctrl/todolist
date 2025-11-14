<?php
require_once 'config.php';

// Handle tambah task (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $title = trim($_POST['title'] ?? '');
    $desc  = trim($_POST['description'] ?? '');
    if ($title !== '') {
        $stmt = mysqli_prepare($conn, "INSERT INTO tasks (title, description) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "ss", $title, $desc);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    header('Location: index.php');
    exit;
}

// Ambil semua task
$res = mysqli_query($conn, "SELECT * FROM tasks ORDER BY created_at DESC");
$tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>To Do List Sederhana</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    :root{--bg:#f4f7f9;--card:#fff;--accent:#2b8cff;--muted:#6b7280}
    *{box-sizing:border-box}
    body{font-family:Inter, system-ui, Arial, sans-serif;background:var(--bg);margin:0;padding:24px}
    .container{max-width:900px;margin:0 auto}
    header{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px}
    h1{margin:0;font-size:20px}
    .card{background:var(--card);padding:16px;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,.06)}
    form{display:flex;gap:8px;flex-wrap:wrap}
    input[type="text"], textarea, select{flex:1;padding:8px;border:1px solid #e6e9ef;border-radius:6px}
    textarea{min-height:60px;resize:vertical}
    button{background:var(--accent);color:#fff;border:none;padding:9px 12px;border-radius:6px;cursor:pointer}
    .list{margin-top:16px}
    .task{display:flex;justify-content:space-between;gap:12px;align-items:flex-start;padding:12px;border-bottom:1px solid #f0f2f5}
    .meta{color:var(--muted);font-size:12px}
    .title{font-weight:600;margin:0 0 6px 0}
    .actions a, .actions form{display:inline-block;margin-left:6px}
    .actions a.button{background:#eef2ff;color:#1e3a8a;padding:6px 8px;border-radius:6px;text-decoration:none;border:1px solid #dbeafe}
    .done{opacity:.6;text-decoration:line-through}
    @media(max-width:600px){form{flex-direction:column}}
  </style>
</head>
<body>
  <div class="container">
    <header>
      <h1>To Do List Sederhana</h1>
      <div class="meta">CRUD | PHP + MySQL</div>
    </header>

    <section class="card">
      <h3>Tambah Task</h3>
      <form method="post" action="index.php" onsubmit="return validateAdd()">
        <input type="hidden" name="action" value="add">
        <input type="text" name="title" id="title" placeholder="Judul task" required>
        <textarea name="description" id="description" placeholder="Deskripsi (opsional)"></textarea>
        <button type="submit">Tambah</button>
      </form>
    </section>

    <section class="card list" style="margin-top:12px">
      <h3>Daftar Task</h3>
      <?php if (empty($tasks)): ?>
        <p class="meta">Belum ada task.</p>
      <?php else: foreach ($tasks as $t): ?>
        <div class="task" id="task-<?=htmlspecialchars($t['id'])?>">
          <div style="flex:1">
            <p class="title <?= $t['status']==='done'? 'done':'' ?>"><?=htmlspecialchars($t['title'])?></p>
            <?php if (trim($t['description'])!==''): ?>
              <div class="meta"><?=nl2br(htmlspecialchars($t['description']))?></div>
            <?php endif; ?>
            <div class="meta">Dibuat: <?=htmlspecialchars($t['created_at'])?> | Status: <?=htmlspecialchars($t['status'])?></div>
          </div>

          <div class="actions">
            <a class="button" href="edit.php?id=<?=urlencode($t['id'])?>">Edit</a>

            <form method="post" action="toggle.php" style="display:inline">
              <input type="hidden" name="id" value="<?=htmlspecialchars($t['id'])?>">
              <input type="hidden" name="status" value="<?= $t['status']==='done' ? 'pending' : 'done' ?>">
              <button type="submit" class="button" style="background:#d1fae5;color:#065f46;border:1px solid #a7f3d0">
                <?= $t['status']==='done' ? 'Mark Pending' : 'Mark Done' ?>
              </button>
            </form>

            <form method="post" action="delete.php" style="display:inline" onsubmit="return confirm('Hapus task ini?')">
              <input type="hidden" name="id" value="<?=htmlspecialchars($t['id'])?>">
              <button type="submit" class="button" style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca">Hapus</button>
            </form>
          </div>
        </div>
      <?php endforeach; endif; ?>
    </section>
  </div>

  <script>
    function validateAdd(){
      var t = document.getElementById('title').value.trim();
      if(t === ''){ alert('Judul tidak boleh kosong'); return false; }
      return true;
    }
  </script>
</body>
</html>

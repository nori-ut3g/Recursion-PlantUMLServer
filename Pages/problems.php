<?php
// コードベースのファイルのオートロード
spl_autoload_extensions(".php");
spl_autoload_register(function ($class) {
    $base_dir = __DIR__ . '/../';
    $file = $base_dir . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

$tableRows = \Models\Problems\ORM::getTableRows();

$perPage = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $perPage;

$pagedItems = array_slice($tableRows, $offset, $perPage);
$totalPages = ceil(count($tableRows) / $perPage);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UML問題集</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body class="bg-light">

<div class="container my-5">
    <table class="table table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Theme</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($pagedItems as $item): ?>
            <tr onclick="window.location.href = './problem.php?id=<?= $item->getID() ?>'">
                <td><?= htmlspecialchars($item->getID(), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($item->getTitle(), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($item->getTheme(), ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a></li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
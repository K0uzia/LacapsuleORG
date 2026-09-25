<?php
require_once dirname(__DIR__) . '/bootstrap.php';
session_start();

$slug = isset($_GET['q']) ? trim(strip_tags((string) $_GET['q'])) : '';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$sql = ressources_base_select();
if ($slug !== '') {
    $sql .= ' WHERE r.slug = :q LIMIT 1';
    $stmt = $db->prepare($sql);
    $stmt->execute(['q' => $slug]);
} elseif ($id > 0) {
    $sql .= ' WHERE r.id = :id LIMIT 1';
    $stmt = $db->prepare($sql);
    $stmt->execute(['id' => $id]);
} else {
    header('Location: index.php?error=ressourceNotFound');
    exit;
}

$res = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$res) {
    header('Location: index.php?error=ressourceNotFound');
    exit;
}

$pageTitle = $res['title'] . ' | La Capsule';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php view('head.php'); ?>
</head>
<body>
<?php view('header.php'); ?>
<main>
    <article class="page-card">
        <p style="color:var(--lcap-muted);margin-top:0">
            <?= htmlspecialchars($res['category']) ?>
            · <?= htmlspecialchars($res['structure_name']) ?>
            · <?= htmlspecialchars($res['auteur']) ?>
        </p>
        <h1><?= htmlspecialchars($res['title']) ?></h1>
        <h2 style="font-size:1.15rem;color:var(--lcap-jaune)"><?= htmlspecialchars($res['subtitle']) ?></h2>
        <p><?= nl2br(htmlspecialchars($res['content'])) ?></p>
        <p><a class="btn btn--ghost" href="index.php#ressources"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Retour</a></p>
    </article>
</main>
<?php view('footer.php'); ?>
</body>
</html>

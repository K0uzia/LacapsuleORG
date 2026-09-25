<?php
require_once dirname(__DIR__) . '/bootstrap.php';
session_start();

$services = require dirname(__DIR__) . '/app/data/services.php';
$slug = preg_replace('/[^a-z0-9\-]/', '', strtolower((string) ($_GET['slug'] ?? ''))) ?? '';
if ($slug === '' || !isset($services[$slug])) {
    http_response_code(404);
    $pageTitle = 'Service introuvable | La Capsule';
    $service = null;
} else {
    $service = $services[$slug];
    $pageTitle = $service['title'] . ' | La Capsule';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php view('head.php'); ?>
</head>
<body>
<?php view('header.php'); ?>
<main>
    <?php if (!$service): ?>
        <p class="alert">Service introuvable.</p>
        <p><a class="btn btn--primary" href="index.php#services"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Retour aux services</a></p>
    <?php else: ?>
        <article class="svc-page">
            <header class="svc-page__hero">
                <div class="svc-page__icon" aria-hidden="true">
                    <i class="fa-solid <?= htmlspecialchars($service['icon']) ?>"></i>
                </div>
                <div>
                    <p class="svc-page__eyebrow"><i class="fa-solid fa-briefcase" aria-hidden="true"></i> Service</p>
                    <h1 class="svc-page__title"><?= htmlspecialchars($service['title']) ?></h1>
                    <p class="svc-page__lead"><?= htmlspecialchars($service['lead']) ?></p>
                    <p class="svc-page__more"><?= htmlspecialchars($service['more']) ?></p>
                </div>
            </header>

            <div class="svc-page__blocks">
                <?php foreach ($service['sections'] as $i => $block): ?>
                    <section class="svc-block">
                        <span class="svc-block__num" aria-hidden="true"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
                        <h2 class="svc-block__title"><?= htmlspecialchars($block['title']) ?></h2>
                        <p class="svc-block__body"><?= htmlspecialchars($block['body']) ?></p>
                    </section>
                <?php endforeach; ?>
            </div>

            <div class="svc-page__actions">
                <?php if (!empty($service['external'])): ?>
                    <a class="btn btn--primary" href="<?= htmlspecialchars($service['external']) ?>" target="_blank" rel="noopener">
                        <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i> Ouvrir CapsuleOS
                    </a>
                <?php endif; ?>
                <a class="btn btn--primary" href="index.php#contact">
                    <i class="fa-solid fa-envelope" aria-hidden="true"></i> Nous contacter
                </a>
                <a class="btn btn--ghost" href="index.php#services">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Tous les services
                </a>
            </div>
        </article>
    <?php endif; ?>
</main>
<?php view('footer.php'); ?>
</body>
</html>

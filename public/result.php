<?php
require_once dirname(__DIR__) . '/bootstrap.php';
session_start();

$q = trim((string) ($_GET['q'] ?? ''));
$pageTitle = 'Recherche | La Capsule';
$items = $q !== '' ? search_all_ressources($db, $q, 48) : [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php view('head.php'); ?>
</head>
<body>
<?php view('header.php'); ?>
<main>
    <section class="section" style="margin-top:0">
        <h1 class="section__title"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i> Résultats</h1>

        <div class="ressources-search" data-live-search>
            <label class="ressources-search__label" for="q">
                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                Affiner la recherche
            </label>
            <div class="ressources-search__form">
                <input
                    class="ressources-search__input"
                    id="q"
                    type="search"
                    value="<?= htmlspecialchars($q) ?>"
                    placeholder="Ex. Ameli, Linux, France Connect…"
                    autocomplete="off"
                    data-live-search-input
                >
            </div>
            <p class="ressources-search__status" data-live-search-status><?= $q !== '' ? count($items) . ' résultat(s)' : '' ?></p>
        </div>

        <div class="feature-grid feature-grid--compact" data-ressource-grid>
            <?php foreach ($items as $item): ?>
                <?php
                $payload = [
                    'type' => 'ressource',
                    'title' => $item['title'],
                    'eyebrow' => strtoupper((string) ($item['ext'] ?? $item['kind'] ?? '')),
                    'subtitle' => $item['subtitle'],
                    'desc' => $item['desc'] ?? '',
                    'image' => $item['image'] ?? null,
                    'file' => $item['file'] ?? '',
                    'download' => $item['download'] ?? $item['url'] ?? '',
                ];
                ?>
                <button
                    type="button"
                    class="feature-card"
                    data-modal-open
                    data-modal-payload="<?= htmlspecialchars(json_encode($payload, JSON_UNESCAPED_UNICODE), ENT_QUOTES) ?>"
                >
                    <div class="feature-card__media">
                        <?php if (!empty($item['image'])): ?>
                            <img src="<?= htmlspecialchars($item['image']) ?>" alt="" loading="lazy">
                        <?php else: ?>
                            <div class="feature-card__fallback"><i class="fa-solid fa-file-pdf" aria-hidden="true"></i></div>
                        <?php endif; ?>
                        <span class="feature-card__badge"><?= htmlspecialchars($payload['eyebrow']) ?></span>
                    </div>
                    <div class="feature-card__body">
                        <h3><?= htmlspecialchars($item['title']) ?></h3>
                        <p><?= htmlspecialchars($item['subtitle']) ?></p>
                        <span class="feature-card__cta">Voir le détail <i class="fa-solid fa-expand" aria-hidden="true"></i></span>
                    </div>
                </button>
            <?php endforeach; ?>
        </div>

        <?php if ($q !== '' && !$items): ?>
            <p class="alert">Aucun résultat.</p>
        <?php endif; ?>

        <p style="margin-top:1.25rem"><a class="btn btn--ghost" href="index.php#ressources"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Retour</a></p>
    </section>
</main>
<?php view('footer.php'); ?>
</body>
</html>

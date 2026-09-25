<?php
require_once dirname(__DIR__) . '/bootstrap.php';
session_start();
$pageTitle = 'Ressources | La Capsule';
$docs = array_map('enrich_file_ressource', list_file_ressources());
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
        <h1 class="section__title"><i class="fa-solid fa-folder-open" aria-hidden="true"></i> Catalogue ressources</h1>
        <p class="section__lead"><?= count($docs) ?> documents. Cliquez une carte pour ouvrir le détail.</p>

        <div class="ressources-search" data-live-search data-live-search-source="local">
            <label class="ressources-search__label" for="q-cat">
                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                Filtrer le catalogue
            </label>
            <div class="ressources-search__form">
                <input
                    class="ressources-search__input"
                    id="q-cat"
                    type="search"
                    placeholder="Ex. Ameli, Linux, CAF…"
                    autocomplete="off"
                    data-live-search-input
                >
            </div>
            <p class="ressources-search__status" data-live-search-status hidden></p>
        </div>

        <div class="feature-grid feature-grid--compact" data-ressource-grid>
            <?php foreach ($docs as $item): ?>
                <?php
                $payload = [
                    'type' => 'ressource',
                    'title' => $item['title'],
                    'eyebrow' => strtoupper($item['ext']),
                    'subtitle' => $item['subtitle'],
                    'desc' => $item['desc'],
                    'image' => $item['image'],
                    'file' => $item['file'],
                    'download' => $item['url'],
                ];
                $hay = strtolower($item['title'] . ' ' . $item['file'] . ' ' . $item['ext']);
                ?>
                <button
                    type="button"
                    class="feature-card"
                    data-modal-open
                    data-search-hay="<?= htmlspecialchars($hay, ENT_QUOTES) ?>"
                    data-modal-payload="<?= htmlspecialchars(json_encode($payload, JSON_UNESCAPED_UNICODE), ENT_QUOTES) ?>"
                >
                    <div class="feature-card__media">
                        <?php if (!empty($item['image'])): ?>
                            <img src="<?= htmlspecialchars($item['image']) ?>" alt="" loading="lazy">
                        <?php else: ?>
                            <div class="feature-card__fallback"><i class="fa-solid fa-file-pdf" aria-hidden="true"></i></div>
                        <?php endif; ?>
                        <span class="feature-card__badge"><?= htmlspecialchars(strtoupper($item['ext'])) ?></span>
                    </div>
                    <div class="feature-card__body">
                        <h3><?= htmlspecialchars($item['title']) ?></h3>
                        <p><?= htmlspecialchars($item['subtitle']) ?></p>
                        <span class="feature-card__cta">Voir le détail <i class="fa-solid fa-expand" aria-hidden="true"></i></span>
                    </div>
                </button>
            <?php endforeach; ?>
        </div>
    </section>
</main>
<?php view('footer.php'); ?>
</body>
</html>

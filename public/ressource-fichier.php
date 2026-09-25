<?php
require_once dirname(__DIR__) . '/bootstrap.php';
session_start();

$slug = preg_replace('/[^a-f0-9]/', '', strtolower((string) ($_GET['slug'] ?? ''))) ?? '';
$item = $slug !== '' ? find_file_ressource_by_slug($slug) : null;

if (!$item) {
    http_response_code(404);
    $pageTitle = 'Ressource introuvable | La Capsule';
} else {
    $pageTitle = $item['title'] . ' | La Capsule';
}

$contentHints = [
    'ameli' => 'Fiche pas à pas pour se connecter à Ameli, créer un compte et retrouver ses documents de santé.',
    'caf' => 'Guide d\'utilisation de la plateforme CAF : connexion, suivi des dossiers et démarches en ligne.',
    'france connect' => 'Présentation de France Connect pour s\'identifier auprès des services publics numériques.',
    'digiposte' => 'Découverte de Digiposte pour stocker et classer ses documents administratifs.',
    'poste' => 'Utilisation de La Poste.net : connexion, interface et services utiles au quotidien.',
    'linux' => 'Ressource autour de Linux : installation, prise en main et bons réflexes.',
    'cv' => 'Aide à la rédaction et à la mise en forme d\'un CV numérique.',
    'mail' => 'Conseils pour gérer sa messagerie, les pièces jointes et la sécurité des e-mails.',
];

function ressource_description(array $item, array $hints): string
{
    $hay = strtolower($item['title'] . ' ' . $item['file']);
    foreach ($hints as $needle => $text) {
        if (str_contains($hay, $needle)) {
            return $text;
        }
    }
    return 'Document mis à disposition par La Capsule pour accompagner les usagers dans leurs démarches numériques. Téléchargez le fichier pour suivre le tuto étape par étape.';
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
    <?php if (!$item): ?>
        <p class="alert">Ressource introuvable.</p>
        <p><a class="btn btn--primary" href="index.php#ressources">Retour aux ressources</a></p>
    <?php else: ?>
        <?php $desc = ressource_description($item, $contentHints); ?>
        <article class="ressource-detail">
            <div class="ressource-detail__media">
                <?php if (!empty($item['image'])): ?>
                    <img src="<?= htmlspecialchars($item['image']) ?>" alt="Illustration : <?= htmlspecialchars($item['title']) ?>">
                <?php else: ?>
                    <div class="ressource-detail__fallback"><i class="fa-solid fa-file-pdf" aria-hidden="true"></i></div>
                <?php endif; ?>
            </div>
            <div class="ressource-detail__body">
                <p class="hero__eyebrow"><?= htmlspecialchars(strtoupper($item['ext'])) ?> · ressource</p>
                <h1 class="section__title" style="margin-top:0.35rem"><?= htmlspecialchars($item['title']) ?></h1>
                <h2 class="ressource-detail__subtitle"><?= htmlspecialchars($item['subtitle']) ?></h2>
                <p class="section__lead" style="margin-bottom:1rem"><?= htmlspecialchars($desc) ?></p>

                <ul class="ressource-detail__meta">
                    <li><i class="fa-solid fa-file" aria-hidden="true"></i> <?= htmlspecialchars($item['file']) ?></li>
                    <li><i class="fa-solid fa-tag" aria-hidden="true"></i> Format <?= htmlspecialchars(strtoupper($item['ext'])) ?></li>
                </ul>

                <div class="ressource-detail__actions">
                    <a class="btn btn--primary" href="<?= htmlspecialchars($item['url']) ?>" target="_blank" rel="noopener">
                        <i class="fa-solid fa-download" aria-hidden="true"></i> Télécharger / ouvrir
                    </a>
                    <a class="btn btn--ghost" href="index.php#ressources">
                        <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Retour
                    </a>
                    <a class="btn btn--ghost" href="ressources.php">Catalogue complet</a>
                </div>
            </div>
        </article>
    <?php endif; ?>
</main>
<?php view('footer.php'); ?>
</body>
</html>

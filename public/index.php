<?php
require_once dirname(__DIR__) . '/bootstrap.php';
session_start();

$pageTitle = 'La Capsule | Chantier d\'insertion numérique à Morlaix';
$servicesCatalog = require dirname(__DIR__) . '/app/data/services.php';
$productions = require dirname(__DIR__) . '/app/data/productions.php';
$featured = featured_file_ressources(3);
$faq = require dirname(__DIR__) . '/app/data/faq.php';

$phoneDisplay = '07 86 44 77 52';
$phoneTel = '+33786447752';
$emailContact = 'contact@lacapsule.org';
$addressLine = '39 Bellevue de la Madeleine';
$addressCity = '29600 Morlaix';
$mapsUrl = 'https://www.google.com/maps/search/?api=1&query=39+Bellevue+de+la+Madeleine+29600+Morlaix';

$services = [
    ['slug' => 'insertion'],
    ['slug' => 'developpement-web'],
    ['slug' => 'hebergement'],
    ['slug' => 'cloud'],
    ['slug' => 'microservices'],
    ['slug' => 'reconditionnement'],
    ['slug' => 'ateliers'],
    ['slug' => 'video'],
    ['slug' => 'capsuleos'],
];

$serviceTeasers = [
    'insertion' => 'Passerelle vers les métiers du numérique, sur projets concrets, avec suivi personnalisé.',
    'developpement-web' => 'Conception et amélioration de sites : design, front, back et maintenance.',
    'hebergement' => 'Mise en ligne, déploiement et accompagnement technique d\'infrastructures maîtrisées.',
    'cloud' => 'Stockage, sauvegarde et synchro adaptés aux structures locales.',
    'microservices' => 'Briques applicatives simples, APIs et automatisations évolutives.',
    'reconditionnement' => 'Contrôle qualité, OS léger et effacement sécurisé des données.',
    'ateliers' => 'Initiation web, réseaux, reconditionnement et éco-conception.',
    'video' => 'Tournage, prise de son et montage pour la diffusion web.',
    'capsuleos' => 'Simulateur de bureaux et outils pédagogiques dans le navigateur.',
];

$servicesByCategory = [];
foreach ($services as $s) {
    $meta = $servicesCatalog[$s['slug']] ?? null;
    if (!$meta) {
        continue;
    }
    $cat = $meta['category'] ?? 'Autres';
    $servicesByCategory[$cat][] = [
        'slug' => $s['slug'],
        'meta' => $meta,
        'teaser' => $serviceTeasers[$s['slug']] ?? $meta['lead'],
    ];
}

$partnersAll = [
    ['file' => 'ulamir.webp', 'alt' => 'ULAMIR-CPIE'],
    ['file' => 'caf.svg', 'alt' => 'CAF'],
    ['file' => 'centres_sociaux.webp', 'alt' => 'Centres sociaux'],
    ['file' => 'finistere.webp', 'alt' => 'Finistère'],
    ['file' => 'DDETS.webp', 'alt' => 'DDETS'],
    ['file' => 'feder.webp', 'alt' => 'FEDER'],
    ['file' => 'logo_resam_quadri_long.webp', 'alt' => 'RESAM'],
    ['file' => 'region_bretagne.webp', 'alt' => 'Région Bretagne'],
    ['file' => 'assurance-maladie-HD.webp', 'alt' => 'Assurance maladie'],
    ['file' => 'pole_emploi.svg', 'alt' => 'France Travail'],
    ['file' => 'morlaix_communaute.webp', 'alt' => 'Morlaix Communauté'],
    ['file' => 'mission_locale.webp', 'alt' => 'Mission Locale'],
    ['file' => 'cci_morlaix.webp', 'alt' => 'CCI Morlaix'],
    ['file' => 'ville_morlaix.webp', 'alt' => 'Ville de Morlaix'],
    ['file' => 'art.webp', 'alt' => 'ART'],
];

$partners = [];
foreach ($partnersAll as $p) {
    $abs = PUBLIC_PATH . '/assets/img/partners/' . $p['file'];
    if (!is_file($abs) || filesize($abs) < 200) {
        // fallback PNG processed versions
        $alt = preg_replace('/\.(webp|svg)$/', '.png', $p['file']);
        if ($alt && is_file(PUBLIC_PATH . '/assets/img/partners/' . $alt)) {
            $p['file'] = $alt;
            $abs = PUBLIC_PATH . '/assets/img/partners/' . $alt;
        } else {
            continue;
        }
    }
    $head = (string) file_get_contents($abs, false, null, 0, 32);
    if (str_contains($head, '<!DOCTYPE') || str_contains($head, '<html')) {
        continue;
    }
    $partners[] = $p;
}
$partnersRowA = $partners;
$partnersRowB = array_reverse($partners);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php view('head.php'); ?>
</head>
<body id="top">
<?php view('header.php'); ?>

<?php if (isset($_GET['send'])): ?>
    <div class="alert" style="width:min(1120px,calc(100% - 2rem));margin:1rem auto 0;">
        <?= $_GET['send'] === 'success' ? 'Message envoyé.' : 'Merci de remplir tous les champs.' ?>
    </div>
<?php endif; ?>

<section
    class="hero"
    id="hero"
    role="region"
    aria-roledescription="carrousel"
    aria-label="Présentation La Capsule"
    tabindex="0"
>
    <div class="hero__viewport">
        <div class="hero__track" data-hero-track>
            <article class="hero__slide" aria-hidden="false" style="--slide-img:url('assets/img/linux-penguin-security-100694867-large.webp')">
                <div class="hero__content">
                    <div class="hero__copy">
                        <p class="hero__eyebrow"><i class="fa-solid fa-cube" aria-hidden="true"></i> ULAMIR-CPIE · Morlaix</p>
                        <h1 class="hero__title">La Capsule</h1>
                        <p class="hero__text">Chantier d'insertion numérique pour les 18-29 ans : reconditionnement, médiation, développement et formation.</p>
                    </div>
                    <a class="btn btn--primary hero__cta" href="#services">Découvrir nos services</a>
                </div>
            </article>
            <article class="hero__slide" aria-hidden="true" style="--slide-img:url('assets/img/rip-windows-seven-featured.jpg')">
                <div class="hero__content">
                    <div class="hero__copy">
                        <p class="hero__eyebrow"><i class="fa-solid fa-door-open" aria-hidden="true"></i> Ouvert au public</p>
                        <h1 class="hero__title">Vendredi 14h-17h</h1>
                        <p class="hero__text">Accueil public le vendredi de 14h à 17h. L'équipe est aussi présente lun · mar · jeu · ven de 9h à 17h (hors accueil public).</p>
                    </div>
                    <a class="btn btn--primary hero__cta" href="#contact">Nous contacter</a>
                </div>
            </article>
            <article class="hero__slide" aria-hidden="true" style="--slide-img:url('assets/img/linux-penguin-security-100694867-large.webp')">
                <div class="hero__content">
                    <div class="hero__copy">
                        <p class="hero__eyebrow"><i class="fa-solid fa-desktop" aria-hidden="true"></i> CapsuleOS</p>
                        <h1 class="hero__title">Apprendre sans installer</h1>
                        <p class="hero__text">Simulateur de bureaux Linux, Windows et macOS dans le navigateur.</p>
                    </div>
                    <a class="btn btn--primary hero__cta" href="https://os.lacapsule.org/" target="_blank" rel="noopener">Ouvrir CapsuleOS</a>
                </div>
            </article>
        </div>
    </div>

    <button type="button" class="hero__arrow hero__arrow--prev" data-hero-prev aria-label="Slide précédent">
        <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
    </button>
    <button type="button" class="hero__arrow hero__arrow--next" data-hero-next aria-label="Slide suivant">
        <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
    </button>

    <div class="hero__dots" data-hero-dots role="tablist" aria-label="Navigation du carrousel"></div>
</section>

<main>
    <section id="services" class="section">
        <h2 class="section__title"><i class="fa-solid fa-briefcase" aria-hidden="true"></i> Nos services</h2>
        <p class="section__lead">Activités et prestations du chantier d'insertion numérique à Morlaix.</p>
        <?php foreach ($servicesByCategory as $catName => $catItems): ?>
            <div class="service-cat">
                <h3 class="service-cat__title"><?= htmlspecialchars($catName) ?></h3>
                <div class="service-grid">
                    <?php foreach ($catItems as $row): ?>
                        <?php
                        $meta = $row['meta'];
                        $payload = [
                            'type' => 'service',
                            'title' => $meta['title'],
                            'eyebrow' => $meta['category'] ?? 'Service',
                            'subtitle' => $row['teaser'],
                            'lead' => $meta['lead'],
                            'more' => $meta['more'],
                            'icon' => $meta['icon'],
                            'sections' => $meta['sections'] ?? [],
                            'external' => $meta['external'] ?? null,
                        ];
                        ?>
                        <button
                            type="button"
                            class="service-card"
                            data-modal-open
                            data-modal-payload="<?= htmlspecialchars(json_encode($payload, JSON_UNESCAPED_UNICODE), ENT_QUOTES) ?>"
                        >
                            <div class="service-card__icon" aria-hidden="true">
                                <i class="fa-solid <?= htmlspecialchars($meta['icon']) ?>"></i>
                            </div>
                            <h3 class="service-card__title"><?= htmlspecialchars($meta['title']) ?></h3>
                            <p class="service-card__desc"><?= htmlspecialchars($row['teaser']) ?></p>
                            <span class="service-card__cta">En savoir plus <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <section id="ressources" class="section section--ressources">
        <h2 class="section__title"><i class="fa-solid fa-folder-open" aria-hidden="true"></i> Ressources les plus consultées</h2>
        <p class="section__lead">Sélection des fiches les plus demandées. La recherche met à jour les cartes en direct.</p>

        <div class="ressources-search" data-live-search>
            <label class="ressources-search__label" for="q">
                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                Rechercher une ressource
            </label>
            <div class="ressources-search__form">
                <input
                    class="ressources-search__input"
                    id="q"
                    type="search"
                    name="q"
                    placeholder="Ex. Ameli, Linux, France Connect…"
                    autocomplete="off"
                    data-live-search-input
                >
            </div>
            <p class="ressources-search__status" data-live-search-status hidden></p>
        </div>

        <div class="feature-grid feature-grid--compact" data-ressource-grid data-featured-html>
            <?php foreach ($featured as $item): ?>
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

        <div class="section__actions">
            <a class="btn btn--primary" href="ressources.php">
                <i class="fa-solid fa-table-list" aria-hidden="true"></i> Voir toutes les ressources
            </a>
        </div>
    </section>

    <section id="productions" class="section">
        <h2 class="section__title"><i class="fa-solid fa-laptop-code" aria-hidden="true"></i> Nos productions</h2>
        <p class="section__lead">Projets développés au sein de La Capsule. Ajoute ici tes réalisations.</p>
        <div class="prod-grid">
            <?php foreach ($productions as $prod): ?>
                <a
                    class="prod-card"
                    href="<?= htmlspecialchars($prod['href']) ?>"
                    <?= !empty($prod['external']) ? 'target="_blank" rel="noopener"' : '' ?>
                >
                    <div class="prod-card__icon" aria-hidden="true">
                        <i class="fa-solid <?= htmlspecialchars($prod['icon']) ?>"></i>
                    </div>
                    <h3 class="prod-card__title"><?= htmlspecialchars($prod['title']) ?></h3>
                    <p class="prod-card__lead"><?= htmlspecialchars($prod['lead']) ?></p>
                    <?php if (!empty($prod['tags'])): ?>
                        <ul class="prod-card__tags">
                            <?php foreach ($prod['tags'] as $tag): ?>
                                <li><?= htmlspecialchars($tag) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <span class="prod-card__cta">
                        Découvrir
                        <i class="fa-solid <?= !empty($prod['external']) ? 'fa-arrow-up-right-from-square' : 'fa-arrow-right' ?>" aria-hidden="true"></i>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="partenaires" class="section section--partners">
        <h2 class="section__title"><i class="fa-solid fa-handshake" aria-hidden="true"></i> Nos partenariats</h2>
        <p class="section__lead">Structures et institutions qui accompagnent La Capsule.</p>
        <div class="partners-marquee" aria-label="Logos partenaires">
            <div class="partners-marquee__row partners-marquee__row--ltr">
                <div class="partners-marquee__track">
                    <?php for ($dup = 0; $dup < 2; $dup++): ?>
                        <?php foreach ($partnersRowA as $p): ?>
                            <div class="partner-pill" title="<?= htmlspecialchars($p['alt']) ?>">
                                <img src="assets/img/partners/<?= htmlspecialchars($p['file']) ?>" alt="<?= htmlspecialchars($p['alt']) ?>" loading="lazy">
                            </div>
                        <?php endforeach; ?>
                    <?php endfor; ?>
                </div>
            </div>
            <div class="partners-marquee__row partners-marquee__row--rtl">
                <div class="partners-marquee__track">
                    <?php for ($dup = 0; $dup < 2; $dup++): ?>
                        <?php foreach ($partnersRowB as $p): ?>
                            <div class="partner-pill" title="<?= htmlspecialchars($p['alt']) ?>">
                                <img src="assets/img/partners/<?= htmlspecialchars($p['file']) ?>" alt="<?= htmlspecialchars($p['alt']) ?>" loading="lazy">
                            </div>
                        <?php endforeach; ?>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </section>

    <section id="faq-home" class="section">
        <h2 class="section__title"><i class="fa-solid fa-circle-question" aria-hidden="true"></i> FAQ</h2>
        <p class="section__lead">Questions fréquentes.</p>
        <?php view('faq-block.php'); ?>
    </section>

    <section id="contact" class="section">
        <h2 class="section__title"><i class="fa-solid fa-envelope-open-text" aria-hidden="true"></i> Nous contacter</h2>
        <p class="section__lead">Devis, rendez-vous ou aide technique.</p>

        <div class="contact-layout">
            <form class="contact-form" action="traitements/mail.php" method="post">
                <h3 class="contact-form__title"><i class="fa-solid fa-paper-plane" aria-hidden="true"></i> Écrire un message</h3>
                <div class="contact-form__row">
                    <div>
                        <label for="mail">Adresse e-mail</label>
                        <input id="mail" type="email" name="mail" placeholder="vous@email.com" required>
                    </div>
                    <div>
                        <label for="subject">Sujet</label>
                        <input id="subject" type="text" name="subject" placeholder="Objet de votre demande" required>
                    </div>
                </div>
                <div class="contact-form__row">
                    <div>
                        <label for="nom">Nom</label>
                        <input id="nom" type="text" name="nom" required>
                    </div>
                    <div>
                        <label for="prenom">Prénom</label>
                        <input id="prenom" type="text" name="prenom" required>
                    </div>
                </div>
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="6" placeholder="Décrivez votre besoin…" required></textarea>
                <input type="hidden" name="honeypot" value="">
                <button class="btn btn--primary" type="submit">
                    <i class="fa-solid fa-paper-plane" aria-hidden="true"></i> Envoyer le message
                </button>
            </form>

            <aside class="contact-aside">
                <div class="contact-info">
                    <h3><i class="fa-solid fa-door-open" aria-hidden="true"></i> Accueil public</h3>
                    <p><strong>Vendredi 14h-17h</strong></p>
                    <p class="contact-info__note">Accueil public le vendredi après-midi. Les autres jours, l'équipe travaille sur place.</p>

                    <h3><i class="fa-solid fa-clock" aria-hidden="true"></i> Présence de l'équipe</h3>
                    <p>Lundi, mardi, jeudi, vendredi · 9h-17h</p>

                    <h3><i class="fa-solid fa-phone" aria-hidden="true"></i> Téléphone</h3>
                    <p><a href="tel:<?= htmlspecialchars($phoneTel) ?>"><?= htmlspecialchars($phoneDisplay) ?></a></p>

                    <h3><i class="fa-solid fa-envelope" aria-hidden="true"></i> E-mail</h3>
                    <p><a href="mailto:<?= htmlspecialchars($emailContact) ?>"><?= htmlspecialchars($emailContact) ?></a></p>

                    <h3><i class="fa-solid fa-location-dot" aria-hidden="true"></i> Adresse</h3>
                    <p>
                        <a href="<?= htmlspecialchars($mapsUrl) ?>" target="_blank" rel="noopener">
                            <?= htmlspecialchars($addressLine) ?><br><?= htmlspecialchars($addressCity) ?>
                        </a>
                    </p>

                    <h3><i class="fa-solid fa-users" aria-hidden="true"></i> Équipe</h3>
                    <ul>
                        <li>Adrien Ferron · encadrant / développeur</li>
                        <li>Bénédicte Compois · directrice</li>
                        <li>Gwen Bellec · CIP</li>
                    </ul>
                </div>

                <div class="contact-map-wrap">
                    <iframe
                        class="contact-map__iframe"
                        title="Carte Google Maps La Capsule Morlaix"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        src="https://maps.google.com/maps?q=39%20Bellevue%20de%20la%20Madeleine%2029600%20Morlaix&z=15&output=embed">
                    </iframe>
                    <a class="contact-map__link" href="<?= htmlspecialchars($mapsUrl) ?>" target="_blank" rel="noopener">
                        <i class="fa-solid fa-map-location-dot" aria-hidden="true"></i> Ouvrir dans Google Maps
                    </a>
                </div>
            </aside>
        </div>
    </section>
</main>

<?php view('footer.php'); ?>
<style>.visually-hidden{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);border:0}</style>
</body>
</html>

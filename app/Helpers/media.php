<?php

/**
 * Liste les ressources fichiers (PDF / documents) depuis uploads/.
 * Les images hashed ne sont plus listées comme ressources : elles servent de couvertures.
 *
 * @return list<array{title:string,type:string,ext:string,url:string,icon:string,file:string}>
 */
function list_file_ressources(?int $limit = null): array
{
    $dirs = [];
    if (is_dir(PUBLIC_PATH . '/uploads/pdf')) {
        $dirs[] = PUBLIC_PATH . '/uploads/pdf';
    } elseif (is_dir(ROOT_PATH . '/uploads/pdf')) {
        $dirs[] = ROOT_PATH . '/uploads/pdf';
    }

    $items = [];
    foreach ($dirs as $dir) {
        foreach (scandir($dir) ?: [] as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $path = $dir . '/' . $file;
            if (!is_file($path)) {
                continue;
            }
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (!in_array($ext, ['pdf', 'doc', 'docx', 'odt'], true)) {
                continue;
            }
            $rel = 'uploads/pdf/' . str_replace('%2F', '/', rawurlencode($file));
            $items[] = [
                'title' => humanize_filename($file),
                'type' => 'pdf',
                'ext' => $ext,
                'url' => $rel,
                'icon' => 'fa-file-pdf',
                'file' => $file,
            ];
        }
    }

    usort($items, static fn ($a, $b) => strcasecmp($a['title'], $b['title']));
    if ($limit !== null) {
        return array_slice($items, 0, $limit);
    }
    return $items;
}

function humanize_filename(string $filename): string
{
    $name = pathinfo($filename, PATHINFO_FILENAME);
    $name = preg_replace('/^[0-9a-f]{16,}_?/i', '', $name) ?? $name;
    $name = str_replace(['_', '-'], [' ', ' '], $name);
    $name = preg_replace('/\s+/', ' ', $name) ?? $name;
    $name = trim($name);
    if ($name === '' || preg_match('/^[0-9a-f]{8,}$/i', $name)) {
        return 'Ressource média';
    }
    return ucwords(strtolower($name));
}

/**
 * @return list<string>
 */
function list_ressource_thumbnails(): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }
    $cache = [];
    $thumbsDir = PUBLIC_PATH . '/uploads/img/thumbnails';
    if (!is_dir($thumbsDir)) {
        return $cache;
    }
    foreach (scandir($thumbsDir) ?: [] as $f) {
        if ($f === '.' || $f === '..' || !is_file($thumbsDir . '/' . $f)) {
            continue;
        }
        $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
        if (!in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'gif'], true)) {
            continue;
        }
        $cache[] = 'uploads/img/thumbnails/' . rawurlencode($f);
    }
    return $cache;
}

/**
 * Associe une couverture : priorite au fichier image homonyme, sinon miniature stable.
 */
function cover_for_file_ressource(string $file, string $slug): ?string
{
    $base = pathinfo($file, PATHINFO_FILENAME);
    $imgDir = PUBLIC_PATH . '/uploads/img';
    $thDir = PUBLIC_PATH . '/uploads/img/thumbnails';
    foreach (['png', 'jpg', 'jpeg', 'webp', 'gif'] as $ext) {
        if (is_file($thDir . '/' . $base . '.' . $ext)) {
            return 'uploads/img/thumbnails/' . rawurlencode($base . '.' . $ext);
        }
        if (is_file($imgDir . '/' . $base . '.' . $ext)) {
            return 'uploads/img/' . rawurlencode($base . '.' . $ext);
        }
    }

    $thumbs = list_ressource_thumbnails();
    if (!$thumbs) {
        return null;
    }
    $idx = hexdec(substr($slug, 0, 6)) % count($thumbs);
    return $thumbs[$idx];
}

/**
 * Normalise une ressource fichier pour cartes / modales / API.
 *
 * @param array{title:string,type:string,ext:string,url:string,icon:string,file?:string} $doc
 * @return array{title:string,subtitle:string,ext:string,url:string,file:string,image:?string,slug:string,kind:string,desc:string}
 */
function enrich_file_ressource(array $doc): array
{
    $file = $doc['file'] ?? basename(urldecode(parse_url($doc['url'], PHP_URL_PATH) ?: $doc['url']));
    $slug = substr(sha1($doc['url']), 0, 16);
    $title = $doc['title'];
    $desc = 'Document mis à disposition par La Capsule pour accompagner les démarches numériques.';
    $hay = strtolower($title . ' ' . $file);
    $hints = [
        'ameli' => 'Fiche pour se connecter à Ameli et retrouver ses documents de santé.',
        'caf' => 'Guide d\'utilisation de la plateforme CAF : connexion et démarches en ligne.',
        'france connect' => 'Présentation de France Connect pour s\'identifier aux services publics.',
        'digiposte' => 'Découverte de Digiposte pour stocker ses documents administratifs.',
        'poste' => 'Utilisation de La Poste.net : connexion et services utiles.',
        'linux' => 'Ressource autour de Linux : installation et prise en main.',
        'cv' => 'Aide à la rédaction et à la mise en forme d\'un CV.',
        'mail' => 'Conseils pour gérer sa messagerie et la sécurité des e-mails.',
    ];
    foreach ($hints as $needle => $text) {
        if (str_contains($hay, $needle)) {
            $desc = $text;
            break;
        }
    }

    return [
        'title' => $title,
        'subtitle' => 'Fiche · ' . strtoupper($doc['ext']),
        'ext' => $doc['ext'],
        'url' => $doc['url'],
        'file' => $file,
        'image' => cover_for_file_ressource($file, $slug),
        'slug' => $slug,
        'kind' => 'pdf',
        'desc' => $desc,
    ];
}

/**
 * @return list<array{title:string,subtitle:string,ext:string,url:string,file:string,image:?string,slug:string,kind:string,desc:string}>
 */
function featured_file_ressources(int $limit = 6): array
{
    $preferred = [
        'ameli', 'caf', 'france connect', 'france_connect', 'digiposte',
        'poste', 'cv', 'linux', 'sauvegard', 'mail', 'libre', 'emploi',
    ];
    $docs = list_file_ressources();
    $scored = [];
    foreach ($docs as $i => $doc) {
        $score = 0;
        $hay = strtolower($doc['title'] . ' ' . $doc['url']);
        foreach ($preferred as $p) {
            if (str_contains($hay, $p)) {
                $score += 10;
            }
        }
        if (str_contains($hay, 'fiche')) {
            $score += 3;
        }
        $scored[] = ['score' => $score, 'i' => $i, 'doc' => $doc];
    }
    usort($scored, static function ($a, $b) {
        if ($a['score'] === $b['score']) {
            return $a['i'] <=> $b['i'];
        }
        return $b['score'] <=> $a['score'];
    });

    $out = [];
    foreach (array_slice($scored, 0, $limit) as $row) {
        $out[] = enrich_file_ressource($row['doc']);
    }
    return $out;
}

function find_file_ressource_by_slug(string $slug): ?array
{
    foreach (list_file_ressources() as $doc) {
        if (substr(sha1($doc['url']), 0, 16) === $slug) {
            return enrich_file_ressource($doc);
        }
    }
    return null;
}

/**
 * @return list<array{title:string,subtitle:string,url:string,kind:string,ext?:string,image:?string,slug?:string,file?:string,desc?:string,download?:string}>
 */
function search_all_ressources(PDO $db, string $q, int $limit = 24): array
{
    $q = trim($q);
    if ($q === '') {
        return [];
    }
    $out = [];
    $needle = strtolower($q);

    foreach (list_file_ressources() as $file) {
        if (str_contains(strtolower($file['title']), $needle) || str_contains(strtolower($file['url']), $needle)) {
            $item = enrich_file_ressource($file);
            $out[] = [
                'title' => $item['title'],
                'subtitle' => $item['subtitle'],
                'url' => $item['url'],
                'download' => $item['url'],
                'kind' => 'pdf',
                'ext' => $item['ext'],
                'image' => $item['image'],
                'slug' => $item['slug'],
                'file' => $item['file'],
                'desc' => $item['desc'],
            ];
        }
        if (count($out) >= $limit) {
            return $out;
        }
    }

    $dbHits = search_ressources($db, $q, 1, $limit);
    foreach ($dbHits['items'] as $row) {
        $image = null;
        if (!empty($row['image'])) {
            $thumb = PUBLIC_PATH . '/uploads/img/thumbnails/' . $row['image'];
            $full = PUBLIC_PATH . '/uploads/img/' . $row['image'];
            if (is_file($thumb)) {
                $image = 'uploads/img/thumbnails/' . rawurlencode($row['image']);
            } elseif (is_file($full)) {
                $image = 'uploads/img/' . rawurlencode($row['image']);
            }
        }
        $out[] = [
            'title' => $row['title'],
            'subtitle' => trim(($row['category'] ?? '') . ' · ' . ($row['subtitle'] ?? 'fiche'), ' ·'),
            'url' => '',
            'download' => '',
            'kind' => 'db',
            'ext' => 'fiche',
            'image' => $image,
            'slug' => $row['slug'] ?? '',
            'file' => '',
            'desc' => (string) ($row['content'] ?? ''),
            'id' => (int) ($row['id'] ?? 0),
        ];
        if (count($out) >= $limit) {
            break;
        }
    }

    return $out;
}

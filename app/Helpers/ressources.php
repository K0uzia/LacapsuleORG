<?php

/**
 * Requête commune ressources (schéma: categories.name, stucture, id_stucture).
 */
function ressources_base_select(): string
{
    return 'SELECT r.id, r.date, r.title, r.slug, r.subtitle, r.content, r.image,
            r.deroule, r.tuto, c.name AS category, u.pseudo AS auteur, s.nom AS structure_name
        FROM ressources r
        INNER JOIN categories c ON r.id_categories = c.id
        INNER JOIN users u ON r.id_users = u.id
        INNER JOIN stucture s ON r.id_stucture = s.id';
}

/**
 * Recherche texte (fonctionne sans index TNTSearch).
 *
 * @return array{hits:int, items:array<int,array>, execution_time:string}
 */
function search_ressources(PDO $db, string $q, int $page = 1, int $perPage = 6): array
{
    $start = microtime(true);
    $term = '%' . $q . '%';
    $countStmt = $db->prepare(
        'SELECT COUNT(*) FROM ressources r
         WHERE r.title LIKE :q OR r.subtitle LIKE :q OR r.content LIKE :q OR r.slug LIKE :q'
    );
    $countStmt->execute(['q' => $term]);
    $hits = (int) $countStmt->fetchColumn();

    $pages = max(1, (int) ceil($hits / $perPage));
    $page = max(1, min($page, $pages));
    $offset = ($page - 1) * $perPage;

    $sql = ressources_base_select() . '
        WHERE r.title LIKE :q OR r.subtitle LIKE :q OR r.content LIKE :q OR r.slug LIKE :q
        ORDER BY r.id DESC
        LIMIT ' . (int) $perPage . ' OFFSET ' . (int) $offset;

    $stmt = $db->prepare($sql);
    $stmt->execute(['q' => $term]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return [
        'hits' => $hits,
        'pages' => $pages,
        'page' => $page,
        'items' => $items,
        'execution_time' => round((microtime(true) - $start) * 1000, 2) . ' ms',
    ];
}

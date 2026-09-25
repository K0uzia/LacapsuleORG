<?php
require_once dirname(__DIR__, 2) . '/bootstrap.php';

use TeamTNT\TNTSearch\TNTSearch;

session_start();
if (isset($_SESSION['user']) && (int) $_SESSION['user']['role'] === 3) {
    $indexDir = STORAGE_PATH . '/index';
    $indexFile = $indexDir . '/ressources.index';
    if (is_file($indexFile)) {
        unlink($indexFile);
    }

    $tnt = new TNTSearch();
    $tntConfig = [
        'storage' => $indexDir . '/',
        'stemmer' => \TeamTNT\TNTSearch\Stemmer\PorterStemmer::class,
    ];
    if (DB_DRIVER === 'sqlite') {
        $tntConfig['driver'] = 'sqlite';
        $tntConfig['database'] = DBNAME;
    } else {
        $tntConfig['driver'] = 'mysql';
        $tntConfig['host'] = DBHOST;
        $tntConfig['database'] = DBNAME;
        $tntConfig['username'] = DBUSER;
        $tntConfig['password'] = DBPASS;
    }
    $tnt->loadConfig($tntConfig);

    $indexer = $tnt->createIndex('ressources.index');
    $indexer->query('SELECT r.id, r.date, r.title, r.subtitle, r.content, r.image,
            c.name AS category, u.pseudo AS auteur, s.nom AS structure
        FROM ressources r
        INNER JOIN categories c ON r.id_categories = c.id
        INNER JOIN users u ON r.id_users = u.id
        INNER JOIN stucture s ON r.id_stucture = s.id');
    $indexer->setLanguage('french');
    $indexer->run();

    header('location: ../gestionRessources.php?generate=success');
} else {
    header('Location: ../login.php');
}

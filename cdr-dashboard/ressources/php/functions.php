<?php

require_once 'db.php';


function debug($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

function numb(string $table, $bdd)
{
    $requete = $bdd->query("SELECT COUNT(*) FROM $table");
    $requete->execute();
    $result = $requete->fetch();
    $nb = $result['COUNT(*)'];
    return $nb;
}

function last(string $table, $bdd, string $champ)
{
    $requete = $bdd->query("SELECT * FROM $table ORDER BY $champ DESC LIMIT 1");
    $requete->execute();
    $result = $requete->fetch();
    return $result;
}

function nbMyRessources($bdd, int $id)
{
    $requete = $bdd->query("SELECT COUNT(*) FROM ressources WHERE id_users = $id");
    $requete->execute();
    $result = $requete->fetch();
    $nb = $result['COUNT(*)'];
    return $nb;
}


function getMyAccount($bdd, $id)
{
    $requete = $bdd->query("SELECT * FROM users WHERE id = $id");
    $requete->execute();
    $result = $requete->fetch();
    return $result;
}

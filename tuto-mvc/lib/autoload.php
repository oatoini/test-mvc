<?php

/* 3. Des alias pour nos namespaces */
const ALIASES = [
    'Plugo' => 'lib',
    'App' => 'src'
];

/* 1. La fonction d'autoload */
spl_autoload_register(function (string $class): void{

/*    2. Éclatement du namespace */

    $namespaceParts = explode('\\', $class); /* Éclatement du namespace */
    /* Cette fonction va exécuter une fonction de callback anonyme qui récupérera dans la variable
    $class le nom de la classe en question.  */


    /* 3. Des alias pour nos namespaces */

    if (in_array($namespaceParts[0], array_keys(ALIASES))) {
        $namespaceParts[0] = ALIASES[$namespaceParts[0]];
    } else {
        throw new Exception('Namespace « ' . $namespaceParts[0] . ' » invalide. Un namespace doit commencer 
        par : « Plugo » ou « App »');
    }
    /* Ici, on vérifie si la première portion du namespace de la classe correspond à une clé du tableau
    ALIASES :
    - Si c'est le cas, on redéfinit la première portion du namespace par le dossier équivalent (lib ou src en fonction).
    - Sinon, on génère une erreur PHP. */

    /* 4. Inclusion dynamique de la classe */

    $filepath = dirname(__DIR__) . '/' . implode('/', $namespaceParts) . '.php';

    if (!file_exists($filepath)) {
        throw new Exception("Fichier « " . $filepath . " » introuvable pour la classe « " . $class . " ». 
        Vérifier le chemin, le nom de la classe ou le namespace");
    }
    require $filepath;

    /* la variable $filepath va contenir le chemin vers la classe en question.
        - Si aucun fichier n'existe, on lève une erreur
        - Sinon, on inclut la classe
    */

});

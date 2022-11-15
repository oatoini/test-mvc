<?php

/* 1. Importation des routes */
require dirname(__DIR__) . '/config/routes.php';

/* 2. Récupération des noms de routes */

$availableRouteNames = array_keys(ROUTES);

/* On récupère toutes les clés du tableau ROUTES avec la fonction array_keys().
Ces clés correspondent aux noms des routes définis dans le fichier de routage. */


/* 3. Appel du contrôleur */
if (isset($_GET['page']) && in_array($_GET['page'], $availableRouteNames)) {
    $route = ROUTES[$_GET['page']];
    $controller = new $route['controller'];
    $controller->{$route['method']}();
}

/* On vérifie si un paramètre $_GET['page'] est bien présent dans l'URL et si ce dernier est bien défini
dans la liste des routes de l'utilisateur. Si c'est le cas, on récupère les informations détaillées
de la route en question (clés : controller et method) pour instancier le contrôleur concerné
et appeler sur l'objet créé la méthode en question. */
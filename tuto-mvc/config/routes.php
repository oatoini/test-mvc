<?php
/* Définition des routes */

const ROUTES = [
    'home' => [
        'controller' => App\Controller\MainController::class,
        'method' => 'home'
    ],
];

/* Ce fichier représente un tableau associatif dans lequel chaque clé correspond au nom d'une route.
Pour chaque route on spécifiera dans les clés :
- controller : le nom de classe de contrôleur à instancier (ainsi que son namespace)
- method : la méthode à appeler depuis l'objet ainsi créé */


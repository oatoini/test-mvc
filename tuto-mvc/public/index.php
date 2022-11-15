<?php

/* Inclusion de notre autoload */
require dirname(__DIR__) . '/lib/autoload.php';

/* Inclusion de notre routeur dans notre controleur frontal */
require dirname(__DIR__) . '/lib/router.php';
/* Le controleur frontale va analyser la requête HTTP et la transmette au contrôleur en charge de retourner
une réponse HTTP adaptée au client. */
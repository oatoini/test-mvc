<?php

namespace App\Controller;
/* Comme toute classe, on la définit dans un namespace. Ici, il s'agit de App\Controller, faisant directement référence
au dossier src/Controller (cf. autoload). */

use Plugo\Controller\AbstractController;
/*Ensuite, on spécifie faire usage de la classe AbstractController citée dans le namespace Plugo\Controller au sein de
 ce fichier. */

class MainController extends AbstractController {
/* On déclare notre class MainController, héritant de la super-classe AbstractController. */

    /* Une méthode = une action */
    public function home() {
        return $this->renderView('pages/main/home.php', ['title' => 'Accueil']);
    }
    /*Ici je souhaite retourner la page templates/main/home.php en lui transmettant dans une variable le titre de la
    page. */

    public function contact() {
        // Imaginons ici traiter la soumission d'un formulaire de contact et envoyer un mail...
        return $this->redirectToRoute('home', ['state' => 'success']);
    }
    /*Ici, je souhaite qu'après traitement, cette méthode redirige vers la route home, en lui transmettant le paramètre
    d'URL GET state=success. */
}
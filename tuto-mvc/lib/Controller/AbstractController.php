<?php
/* Introduction de notre première classe : le contrôleur global. */
namespace Plugo\Controller;
/* on définit un namespace pour cette classe. Ici, il s'agit de Plugo\Controller */

/* Cette classe est déclarée avec le mot-clé abstract, cela signifie qu'elle ne sera jamais instanciée.
Il s'agit donc plutôt d'une super-classe (parent) dont le rôle va être de mettre à disposition des méthodes. */
abstract class AbstractController {

/* Le rôle de ce contrôleur particulier va être d'implémenter 2 méthodes génériques de notre framework
pour retourner une page HTML et effectuer des redirections.
 */

    /* Méthode 1 : retourner une vue (une page html) */

    protected function renderView(string $template, array $data = []): string {
        /* La méthode renderView() est déclarée comme protected, cela signifie qu'elle ne sera accessible
        que depuis les classes qui en héritent. Cette fonction possède deux paramètres :
            - template (string) : le nom du template HTML à retourner (requis)
            - data (array) : un tableau de données à transmettre au template (optionnel) */

        $templatePath = dirname(__DIR__, 2) . '/templates/' . $template;
        /* Le chemin vers le template est stocké dans une variable $templatePath.  */
        return require_once dirname(__DIR__, 2) . '/templates/layout.php';
        /*  Le chemin vers le layout du site web est systématiquement inclus et retourné par la fonction.
        La fonction dirname() prend ici un second argument 2, afin de spécifier qu'on souhaite remonter de deux niveaux
        d'arborescence (2 dossiers).
        */
    }

    /* Méthode 2 : effectuer une redirection */

    protected function redirectToRoute(string $name, array $params = []): void {
        /* La méthode redirectToRoute() est également déclarée comme protected, cela signifie qu'elle ne sera accessible
        que depuis les classes qui en héritent. Cette fonction possède deux paramètres :
            - name (string) : le nom de la route vers laquelle rediriger (requis)
            - params (array) : des paramètres d'URL GET (optionnel)
        */
        $uri = $_SERVER['SCRIPT_NAME'] . "?page=" . $name;
        /* $_SERVER['SCRIPT_NAME'] contient le chemin complet ainsi que le nom du fichier courant. $uri va ainsi
        générer l'URL complète correspondante à la route vers laquelle on souhaite rediriger en définissant le
        paramètre $_GET['page'].
        SCRIPT_NAME fait référence à la clé du $_SERVER et ce script est le script d'index.php ici
        */
        if (!empty($params)) {
            $strParams = [];
            foreach ($params as $key => $val) {
                array_push($strParams, urlencode((string) $key) . '=' . urlencode((string) $val));
            }
            /*
            Si des paramètres d'URL sont transmis via la fonction redirectToRoute(), foreach() parcourt le tableau
            associatif $params de couples clés-valeurs des paramètres GET
            */
            $uri .= '&' . implode('&', $strParams);
            /*pour les écrire dans un format d’URL standard. Exemple : $params = ['nb' => 12, 'order' => 'ASC']
            deviendra &nb=12&order=ASC */
        }

        header("Location: " . $uri);
        /* header() : effectue la redirection vers l'URL générée dynamiquement.*/
        die;
    }


}
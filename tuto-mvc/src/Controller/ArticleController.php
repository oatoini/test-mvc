<?php

namespace App\Controller;

use App\Manager\ArticleManager;
use Plugo\Controller\AbstractController;

class ArticleController extends AbstractController {

    /* Voici un exemple de méthode de contrôleur dans laquelle je récupère et transmets à une vue
    tous les articles du blog via ArticleManager.*/

    public function index() {
        $articleManager = new ArticleManager();

        return $this->renderView('article/index.php', [
        'articles' => $articleManager->findAll()
        ]);
    }


    /* Voici un exemple de méthode de contrôleur dans laquelle j'insère en BDD un nouvel article via ArticleManager
    et l'entité Article.
*/
    public function add() {
        if (!empty($_POST)) {
            $article = new Article();
            $articleManager = new ArticleManager();
            $article->setTitle($_POST['title']);
            $article->setDescription($_POST['description']);
            $article->setContent($_POST['content']);
            $articleManager->add($article);
            return $this->redirectToRoute('article_index');
        }
        return $this->renderView('article/add.php');
    }
}
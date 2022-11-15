<?php
namespace App\Controller;

use Plugo\Manager\AbstractManager;
use App\Entity\Article;

class ArticleManager extends AbstractManager {

    /* 1. Récupération par id */
    public function find(int $id) {
        return $this->readOne(Article::class, [ 'id' => $id ]);
    }

    /* 2. Récupération unique par filtre */
    public function findOneBy(array $filters) {
        return $this->readOne(Article::class, $filters);
    }

    /* 3. Récupération multiple*/
    public function findAll() {
        return $this->readMany(Article::class);
    }

    /* 4. Récupération multiple par filtre*/
    public function findBy(array $filters, array $order = [], int $limit = null, int $offset = null) {
        return $this->readMany(Article::class, $filters, $order, $limit, $offset);
    }

    /* 5. Création d'une ressource */
    public function add(Article $article) {
        return $this->create(Article::class, [
                'title' => $article->getTitle(),
                'description' => $article->getDescription(),
                'content' => $article->getContent()
            ]
        );
    }

    /* 6. Modification d'une ressource */
    public function edit(Article $article) {
        return $this->update(Article::class, [
            'title' => $article->getTitle(),
            'description' => $article->getDescription(),
            'content' => $article->getContent()
        ],
            $article->getId()
        );
    }
    /* 7. Suppression d'une ressource */
    public function remove(Article $article) {
        return $this->delete(Article::class, $article->getId());
    }
}
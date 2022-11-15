<?php

namespace Plugo\Manager;
require dirname(__DIR__, 2) . '/config/database.php';
/*Les gestionnaires (en anglais « managers ») constituent les services permettant de manipuler les données. Ce sont
eux qui vont jouer le rôle d'interface entre nos contrôleurs et notre BDD. Ils implémenteront donc les CRUD de nos entités. */


abstract class AbstractManager {
/* AsbtractManager jouant le rôle de gestionnaire global pour les modèles de notre application, il se situe donc
dans le namespace Plugo\Manager.
Cette classe est déclarée avec le mot-clé abstract, cela signifie que nous ne pourrons pas l'instancier.
Son rôle sera de partager des propriétés / méthodes aux classes qui en hériteront.
*/

    /*Méthode 1 : connexion à la BDD */
    private function connect(): \PDO {
        /*Ensuite, créons une méthode privée connect(), dont le but est de retourner la connexion à la base de données,
         établie via la classe PDO.*/
        $db = new \PDO(
            "mysql:host=" . DB_INFOS['host'] . ";port=" . DB_INFOS['port'] . ";dbname=" . DB_INFOS['dbname'],
            DB_INFOS['username'],
            DB_INFOS['password']
        );
        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        /*permet d'afficher les potentielles erreurs de requêtes SQL.*/
        $db->exec("SET NAMES utf8");
        /*permet de prévenir d'éventuels problèmes d'encodage de certains caractères spéciaux.*/
        return $db;
    }

    /*Méthode 2 : Exécution d'une requête SQL */
    private function executeQuery(string $query, array $params = []): \PDOStatement {
        $db = $this->connect();
        $stmt = $db->prepare($query);
        foreach ($params as $key => $param) $stmt->bindValue($key, $param);
        $stmt->execute();
        return $stmt;
        /*La méthode executeQuery() comporte 2 paramètres :
            - $query (string) : la requête SQL à exécuter (SELECT, INSERT, UPDATE, DELETE...)
            - $params (array) : un tableau de paramètres à binder si la requête contient des marqueurs :.*/
    }

    /*Méthode 3 : conversion namespace → table */
    private function classToTable(string $class): string {
        $tmp = explode('\\', $class);
        return strtolower(end($tmp));
    }
    /*La méthode classToTable() comporte 1 paramètre :
        - $class (string) : le namespace d'une entité.
    D'abord, explode() éclate le namespace de la classe sur le caractère \. Ensuite, end() retourne la dernière valeur du tableau $tmp. Enfin, strtolower() retourne cette chaîne en minuscule.
    Par exemple, le namespace App\Entity\Article serait converti en article.*/

    /* 1. Lecture d'une seule ressource */

    protected function readOne(string $class, array $filters): mixed {
        $query = 'SELECT * FROM ' . $this->classToTable($class) . ' WHERE ';
        foreach (array_keys($filters) as $filter) {
            $query .= $filter . " = :" . $filter;
            if ($filter != array_key_last($filters)) $query .= 'AND ';
        }
        $stmt = $this->executeQuery($query, $filters);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, $class);
        return $stmt->fetch();
    }
    /* setFetchMode(\PDO::FETCH_CLASS, $class) nous permet de spécifier que nous souhaitons mapper les données
    récupérées au sein de l'entité spécifiée par le paramètre $class. La méthode readOne() comporte 2 paramètres :
        - $class (string) : le namespace d'une entité.
        - $filters (array) : un tableau de critères de filtre de la ressource.
    Cette méthode retournera :
        - En cas de succès : un objet
        - En cas d'échec : false */


    /* 2. Lecture de plusieurs ressources */

    protected function readMany(string $class, array $filters = [], array $order = [], int $limit = null, int $offset = null): mixed {
        $query = 'SELECT * FROM ' . $this->classToTable($class);
        if (!empty($filters)) {
            $query .= ' WHERE ';
            foreach (array_keys($filters) as $filter) {
                $query .= $filter . " = :" . $filter;
                if ($filter != array_key_last($filters)) $query .= 'AND ';
            }
        }
        if (!empty($order)) {
            $query .= ' ORDER BY ';
            foreach ($order as $key => $val) {
                $query .= $key . ' ' . $val;
                if ($key != array_key_last($order)) $query .= ', ';
            }
        }
        if (isset($limit)) {
            $query .= ' LIMIT ' . $limit;
            if (isset($offset)) {
                $query .= ' OFFSET ' . $offset;
            }
        }
        var_dump($query);
        $stmt = $this->executeQuery($query, $filters);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, $class);
        return $stmt->fetchAll();
    }
    /*setFetchMode(\PDO::FETCH_CLASS, $class) nous permet de spécifier que nous souhaitons mapper les données
    récupérées au sein de l'entité spécifiée par le paramètre $class. La méthode readMany() comporte 5 paramètres :
        - $class (string) : le namespace d'une entité.
        - (optionnel) $filters (array) : un tableau de critères de filtre des ressources.
        - (optionnel) $order (array) : un tableau de critères de tri des ressources.
        - (optionnel) $limit (array) : un nombre limitant la quantité de ressources à récupérer.
        - (optionnel) $offset (array) : un nombre spécifiant un décalage pour la récupération de ressources ("à partir de telle ligne").
    Cette méthode retournera :
        - En cas de succès : un tableau d'objets. Un tableau d'objets issus de la même classe est aussi appelé
          « collection ».
        -En cas d'échec : false*/

    /* 3. Création d'une ressource */

    protected function create(string $class, array $fields): \PDOStatement {
        $query = "INSERT INTO " . $this->classToTable($class) . " (";
        foreach (array_keys($fields) as $field) {
            $query .= $field;
            if ($field != array_key_last($fields)) $query .= ', ';
        }
        $query .= ') VALUES (';
        foreach (array_keys($fields) as $field) {
            $query .= ':' . $field;
            if ($field != array_key_last($fields)) $query .= ', ';
        }
        $query .= ')';
        return $this->executeQuery($query, $fields);
    }

    /*La méthode create() comporte 2 paramètres :
        - $class (string) : le namespace d'une entité.
        - $fields (array) : les champs à enregistrer en BD (clé-valeur). Le tableau associatif reçu dans cette variable va permettre de construire, à partir de ses clés, la requête préparée en y précisant tous les champs concernés par l'insertion.
    Cette méthode retournera :
        - En cas de succès : une instance de PDOStatement
        - En cas d'échec : false*/

    /* 4. Modification d'une ressource */

    protected function update(string $class, array $fields, int $id): \PDOStatement {
        $query = "UPDATE " . $this->classToTable($class) . " SET ";
        foreach (array_keys($fields) as $field) {
            $query .= $field . " = :" . $field;
            if ($field != array_key_last($fields)) $query .= ', ';
        }
        $query .= ' WHERE id = :id';
        $fields['id'] = $id;
        return $this->executeQuery($query, $fields);
    }
    /*La méthode update() comporte 3 paramètres :
        - $class (string) : le namespace d'une entité.
        - $fields (array) : les champs à modifier en BD (clé-valeur). Le tableau associatif reçu dans cette variable va permettre de construire, à partir de ses clés, la requête préparée en y précisant tous les champs concernés par l'édition.
        - $id (string) : l'identifiant de la ressource à éditer.
    Cette méthode retournera :
        - En cas de succès : une instance de PDOStatement
        - En cas d'échec : false*/

    /* 5. Suppression d'une ressource */

    protected function delete(string $class, int $id): \PDOStatement {
        $query = "DELETE FROM " . $this->classToTable($class) . " WHERE id = :id";
        return $this->executeQuery($query, [ 'id' => $id ]);
    }
    /*La méthode delete() comporte 2 paramètres :
        - $class (string) : le namespace d'une entité
        - $id (int) : l'identifiant de la ressource à supprimer
    Cette méthode retournera :
        - En cas de succès : une instance de PDOStatement
        - En cas d'échec : false */

}
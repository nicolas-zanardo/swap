<?php

require_once __DIR__ . "/../Database.php";

class AnnouncesCategoriesDB extends Database
{

    public function fetchAll(): array
    {
        $category= $this->sql("SELECT * FROM categorie");
        return $category->fetchAll();
    }

    public function fetchOne($param, $value)
    {
        $category = $this->sql("SELECT * FROM categorie WHERE $param=:value", array(
            'value' => $value
        ));
        return $category->fetch();
    }

    public function createOne($category)
    {
        $this->sql("INSERT INTO categorie VALUES (NULL, :titre, :motscle)", array(
            'titre' => $category['titre'],
            'motscle' => $category['motscle']
        ));
//        return $this->fetchOne($this->pdo()->lastInsertId());
    }

    public function updateOne($category)
    {
        $this->sql("UPDATE categorie SET titre=:titre, motscles=:motscles WHERE id_categorie=:id_categorie", array(
            'titre' => $category['titre'],
            'motscles' => $category['motscles'],
            'id_categorie' => $category['id_categorie']
        ));
    }

    public function deleteOne(int $id)
    {
        $this->sql("DELETE FROM categorie WHERE id_categorie=:id_categorie", array(
            'id_categorie' => $id
        ));

    }


    public function countCategory()
    {
        $category = $this->sql("SELECT titre FROM categorie ");
        return $category->rowCount();
    }
}
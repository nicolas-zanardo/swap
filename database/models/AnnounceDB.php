<?php

require_once __DIR__ . '/../Database.php';
require_once 'PhotoDB.php';

class AnnounceDB extends Database
{
    private $photos;

    public function __construct()
    {
        $this->photos = new PhotoDB();
    }

    public function findAll()
    {
        $announce = $this->sql("
            SELECT annonce.id_annonce ,annonce.titre, annonce.description_courte, annonce.prix, annonce.photo, annonce.id_membre, annonce.id_photo, categorie.titre as categorie_titre, categorie.motscles as categorie_keyword FROM annonce
            INNER JOIN categorie on categorie.id_categorie = annonce.id_categorie "
        );
        return $announce->fetchAll();
    }

    public function findAllByUserID(int $id)
    {
        $announce = $this->sql("
            SELECT annonce.id_annonce, annonce.id_membre, annonce.titre, annonce.description_courte, annonce.prix, annonce.photo, annonce.id_annonce, annonce.latlng_log, annonce.latlng_lat, categorie.titre as categorie_titre FROM annonce
            INNER JOIN categorie on categorie.id_categorie = annonce.id_categorie 
            WHERE id_membre=:id_membre;
        ", array(
            'id_membre' => $id
        ));
        return $announce->fetchAll();
    }

    public function editArticle($id)
    {
        $announce = $this->sql("
        SELECT annonce.* , photo.*, membre.* FROM annonce
        INNER JOIN photo on photo.id_photo = annonce.id_photo
        INNER JOIN membre on membre.id_membre = annonce.id_membre
        WHERE id_annonce=:id
        ", array('id' => $id));

        return $announce->fetch();
    }

    public function findAnnounce($param, $value)
    {
        $announce = $this->sql("
            SELECT * FROM annonce WHERE $param=:value;
        ", array(
            'value' => $value
        ));
        return $announce->fetch();
    }

    public function countAnnounceByUser($idUser)
    {
        $announce = $this->sql("
            SELECT id_membre FROM annonce WHERE id_membre=:id
        ", array(
            'id' => $idUser
        ));
        return $announce->rowCount();
    }

    public function countAnnounces()
    {
        $announce = $this->sql("
            SELECT id_membre FROM annonce
        ");
        return $announce->rowCount();
    }

    public function findAllPhotoByIdUser($idUser)
    {
        $announce = $this->sql('
            SELECT id_membre, id_photo FROM annonce WHERE id_membre=:id
        ', array(
            'id' => $idUser
        ));
        return $announce->fetchAll();
    }

    public function createOne($announce, array $photos)
    {

        $this->sql("INSERT INTO annonce VALUES (null,:titre, :description_courte, :description_longue, :prix, :photo, :adresse, :pays, :ville, :cp, :latlng_log, :latlng_lat, NOW(),:id_categorie, :id_photo, :id_membre)", array(
            'titre' => $announce['titre'],
            'description_courte' => $announce["description_courte"],
            'description_longue' => $announce['description_longue'],
            'prix' => $announce['prix'],
            'photo' => $announce['photo'],
            'adresse' => $announce['adresse'],
            'pays' => $announce['pays'],
            'ville' => $announce['ville'],
            'cp' => $announce['cp'],
            'latlng_log' => $announce['latlng_log'],
            'latlng_lat' => $announce['latlng_lat'],
            'id_categorie' => $announce['id_categorie'],
            'id_photo' => $this->photos->createOne($photos),
            'id_membre' => $announce['id_membre']

        ));

        return $this->pdo()->lastInsertId('id_annonce');
    }

    public function updateAnnounce(array $announce)
    {
        $this->sql("UPDATE annonce SET 
                   titre=:titre, 
                   description_courte=:description_courte,  
                   description_longue=:description_longue, 
                   prix=:prix, 
                   photo=:photo, 
                   adresse=:adresse, 
                   pays=:pays, 
                   ville=:ville, 
                   cp=:cp, 
                   latlng_log=:latlng_log,
                   latlng_lat=:latlng_lat,
                   id_categorie=:id_categorie
                   WHERE id_annonce=:id_annonce", array(
            'titre' => $announce['titre'],
            'description_courte' => $announce["description_courte"],
            'description_longue' => $announce['description_longue'],
            'prix' => $announce['prix'],
            'photo' => $announce['photo'],
            'adresse' => $announce['adresse'],
            'pays' => $announce['pays'],
            'ville' => $announce['ville'],
            'cp' => $announce['cp'],
            'latlng_log' => $announce['latlng_log'],
            'latlng_lat' => $announce['latlng_lat'],
            'id_categorie' => $announce['id_categorie'],
            'id_annonce' => $announce['id_annonce']
        ));
    }


    /**
     * ##############
     *  - HOME PAGE
     * ##############
     * All request to search announces on homepage
     */

    public function countMaxPrice($getIdCategory, $getIdUser)
    {
        if ($getIdUser && !$getIdCategory) {
            $minPriceAnnounce = $this->sql("
                    SELECT MAX(prix), membre.id_membre FROM annonce
                    LEFT JOIN membre on membre.id_membre = annonce.id_membre 
                    WHERE annonce.id_membre=$getIdUser
                    ");
        } elseif ($getIdCategory && !$getIdUser) {
            $minPriceAnnounce = $this->sql("
                    SELECT MAX(prix), categorie.id_categorie FROM annonce
                    LEFT JOIN categorie on categorie.id_categorie = annonce.id_categorie
                    WHERE categorie.id_categorie=$getIdCategory
                    ");
        } elseif ($getIdCategory && $getIdUser) {
            $minPriceAnnounce = $this->sql("
                    SELECT MAX(prix), categorie.id_categorie, membre.id_membre FROM annonce
                    LEFT JOIN categorie on categorie.id_categorie = annonce.id_categorie
                    LEFT JOIN membre on membre.id_membre = annonce.id_membre
                    WHERE categorie.id_categorie=$getIdCategory AND annonce.id_membre=$getIdUser
                    ");
        } else {
            $minPriceAnnounce = $this->sql("
                    SELECT MAX(prix) FROM annonce;
          ");
        }
        return $minPriceAnnounce->fetch()['MAX(prix)'];
    }

    public function countMinPrice($getIdCategory, $getIdUser)
    {
        if ($getIdUser && !$getIdCategory) {
            $minPriceAnnounce = $this->sql("
                    SELECT MIN(prix), membre.id_membre FROM annonce
                    LEFT JOIN membre on membre.id_membre = annonce.id_membre 
                    WHERE annonce.id_membre=$getIdUser
                    ");
        } elseif ($getIdCategory && !$getIdUser) {
            $minPriceAnnounce = $this->sql("
                    SELECT MIN(prix), categorie.id_categorie FROM annonce
                    LEFT JOIN categorie on categorie.id_categorie = annonce.id_categorie
                    WHERE categorie.id_categorie=$getIdCategory
                    ");
        } elseif ($getIdCategory && $getIdUser) {
            $minPriceAnnounce = $this->sql("
                    SELECT MIN(prix), categorie.id_categorie, membre.id_membre FROM annonce
                    LEFT JOIN categorie on categorie.id_categorie = annonce.id_categorie
                    LEFT JOIN membre on membre.id_membre = annonce.id_membre 
                    WHERE categorie.id_categorie=$getIdCategory AND annonce.id_membre=$getIdUser
                    ");
        } else {
            $minPriceAnnounce = $this->sql("
                    SELECT MIN(prix) FROM annonce;
          ");
        }
        return $minPriceAnnounce->fetch()['MIN(prix)'];
    }


    public function requestAnnounce($getIdCategory, $getIdUser, $price) {
        if ($getIdUser && !$getIdCategory && !$price) {
            $minPriceAnnounce = $this->sql("
                    SELECT annonce.id_annonce ,annonce.titre, annonce.description_courte, annonce.prix, annonce.photo, annonce.id_membre, categorie.titre as categorie_titre, categorie.motscles as categorie_keyword FROM annonce
                    INNER JOIN categorie on categorie.id_categorie = annonce.id_categorie
                    WHERE annonce.id_membre=$getIdUser
                    ");
        } elseif ($getIdCategory && !$getIdUser && !$price) {
            $minPriceAnnounce = $this->sql("
                    SELECT annonce.id_annonce ,annonce.titre, annonce.description_courte, annonce.prix, annonce.photo, annonce.id_membre, categorie.titre as categorie_titre, categorie.motscles as categorie_keyword FROM annonce
                    INNER JOIN categorie on categorie.id_categorie = annonce.id_categorie
                    WHERE annonce.id_categorie=$getIdCategory
                    ");
        } elseif ($price && $getIdUser && !$getIdCategory) {
            $minPriceAnnounce = $this->sql("
                    SELECT annonce.id_annonce ,annonce.titre, annonce.description_courte, annonce.prix, annonce.photo, annonce.id_membre, categorie.titre as categorie_titre, categorie.motscles as categorie_keyword FROM annonce
                    INNER JOIN categorie on categorie.id_categorie = annonce.id_categorie
                    WHERE annonce.prix<=$price AND annonce.id_membre=$getIdUser
                    ");
        } elseif ($price && $getIdCategory && !$getIdUser ) {
            $minPriceAnnounce = $this->sql("
                    SELECT annonce.id_annonce ,annonce.titre, annonce.description_courte, annonce.prix, annonce.photo, annonce.id_membre, categorie.titre as categorie_titre, categorie.motscles as categorie_keyword FROM annonce
                    INNER JOIN categorie on categorie.id_categorie = annonce.id_categorie
                    WHERE annonce.prix<=$price AND annonce.id_categorie=$getIdCategory
                    ");
        } elseif ($price && !$getIdUser && !$getIdCategory) {
            $minPriceAnnounce = $this->sql("
                    SELECT annonce.id_annonce ,annonce.titre, annonce.description_courte, annonce.prix, annonce.photo, annonce.id_membre, categorie.titre as categorie_titre, categorie.motscles as categorie_keyword FROM annonce
                    INNER JOIN categorie on categorie.id_categorie = annonce.id_categorie
                    WHERE annonce.prix<=$price
                    ");
        }  elseif ($getIdCategory && $getIdUser && $price) {
            $minPriceAnnounce = $this->sql("
                    SELECT annonce.id_annonce ,annonce.titre, annonce.description_courte, annonce.prix, annonce.photo, annonce.id_membre, categorie.titre as categorie_titre, categorie.motscles as categorie_keyword FROM annonce
                    INNER JOIN categorie on categorie.id_categorie = annonce.id_categorie
                    WHERE annonce.id_membre=$getIdUser AND annonce.id_categorie=$getIdCategory AND annonce.prix<=$price
                    ");
        } elseif ($getIdCategory && $getIdUser) {
            $minPriceAnnounce = $this->sql("
                    SELECT annonce.id_annonce ,annonce.titre, annonce.description_courte, annonce.prix, annonce.photo, annonce.id_membre, categorie.titre as categorie_titre, categorie.motscles as categorie_keyword FROM annonce
                    INNER JOIN categorie on categorie.id_categorie = annonce.id_categorie
                    WHERE annonce.id_membre=$getIdUser AND annonce.id_categorie=$getIdCategory
                    ");
        } else {
            $minPriceAnnounce = $this->sql("
                SELECT annonce.id_annonce ,annonce.titre, annonce.description_courte, annonce.prix, annonce.photo, annonce.id_membre, categorie.titre as categorie_titre, categorie.motscles as categorie_keyword FROM annonce
                INNER JOIN categorie on categorie.id_categorie = annonce.id_categorie 
          ");
        }
        return $minPriceAnnounce->fetchAll();
    }
}
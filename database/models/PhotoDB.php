<?php
require_once __DIR__ . '/../Database.php';

class PhotoDB extends Database
{

    public function fetchOne($param, $value)
    {
        $photo = $this->sql("SELECT * FROM photo WHERE $param=:value", array(
            'value' => $value
        ));
        return $photo->fetch();
    }


    public function deletePhoto($idPhoto) {
        $this->sql("DELETE FROM photo WHERE id_photo=:id", array( 'id' => $idPhoto));
    }

    public function createOne($announce)
    {

        $this->sql("INSERT INTO photo VALUES (NULL, :photo1, :photo2, :photo3, :photo4, :photo5)", array(
            'photo1' => $announce['photo1'],
            'photo2' => $announce["photo2"],
            'photo3' => $announce['photo3'],
            'photo4' => $announce['photo4'],
            'photo5' => $announce['photo5']
        ));

        return $this->pdo()->lastInsertId('id_photo');
    }

    public function updateOne($announce, $id)
    {
        $this->sql("UPDATE photo SET photo1=:photo1, photo2=:photo2, photo3=:photo3, photo4=:photo4, photo5=:photo5 WHERE id_photo=:id", array(
            'photo1' => $announce['photo1'],
            'photo2' => $announce["photo2"],
            'photo3' => $announce['photo3'],
            'photo4' => $announce['photo4'],
            'photo5' => $announce['photo5'],
            'id'=> $id
        ));


    }

}
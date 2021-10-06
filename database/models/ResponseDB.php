<?php

require_once __DIR__ . '/../Database.php';
require_once __DIR__ . '/QuestionDB.php';

class ResponseDB extends Database
{

    public function createResponse($response, $idQuestion)
    {
        $this->sql("
            INSERT INTO reponse VALUES(null, :reponse, NOW(),  :id_membre, :id_question)", array(
            'reponse' => $response['reponse'],
            'id_membre' => $response['id_membre'],
            'id_question' => $response['id_question']
        ));
        $this->sql("
            UPDATE question SET pending=0 WHERE id_question=$idQuestion;
        ");
    }

    public function findOneByID($id)
    {
      $response = $this->sql("
            SELECT * FROM reponse WHERE id_question=$id
      ");
      return $response->fetch();
    }
}
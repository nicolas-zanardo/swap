<?php

require_once __DIR__ . '/../Database.php';


class QuestionDB extends Database
{
    public function createQuestion($question)
    {
        $this->sql("INSERT INTO question VALUES (null, :question, '1', NOW(), :id_membre, :id_annonce)", array(
            'question' => $question['question'],
            'id_membre' => $question['id_membre'],
            'id_annonce' => $question['id_annonce']
        ));

        return $this->pdo()->lastInsertId();
    }

    public function fetchAllQuestionByIDAnnounce($idAnnounce)
    {
        $allQuestionByIdAnnounce = $this->sql("
            SELECT question.*, reponse.reponse FROM question 
            LEFT JOIN reponse on question.id_question = reponse.id_question 
            WHERE question.id_annonce=$idAnnounce;
        ");
        return $allQuestionByIdAnnounce->fetchAll();
    }

    public function countQuestionByUser($idUser)
    {
        $announce = $this->sql("
            SELECT  question.*, annonce.id_membre FROM question 
            LEFT JOIN annonce on annonce.id_annonce = question.id_annonce
            WHERE annonce.id_membre=:id AND question.pending=1
        ", array(
            'id' => $idUser
        ));
        return $announce->rowCount();
    }

    public function countAllQuestionByUser($idUser)
    {
        $announce = $this->sql("
            SELECT  question.*, annonce.id_membre FROM question 
            LEFT JOIN annonce on annonce.id_annonce = question.id_annonce
            WHERE annonce.id_membre=:id
        ", array(
            'id' => $idUser
        ));
        return $announce->rowCount();
    }

    public function countAllQuestions()
    {
        $announce = $this->sql("
            SELECT question  FROM question
        ");
        return $announce->rowCount();
    }

    public function countAllQuestionsPending()
    {
        $announce = $this->sql("
            SELECT question  FROM question WHERE pending=1;
        ");
        return $announce->rowCount();
    }

    public function findQuestionByUser($idUser)
    {
        $announce = $this->sql("
            SELECT  question.*, annonce.* FROM question 
            LEFT JOIN annonce on annonce.id_annonce = question.id_annonce
            WHERE annonce.id_membre=$idUser
        ", array(
            'id' => $idUser
        ));
        return $announce->fetchAll();
    }
}
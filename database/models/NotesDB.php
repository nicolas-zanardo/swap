<?php

require_once __DIR__ . '/../Database.php';

class NotesDB extends Database
{
    public function countNoteByUser($idUser) {
        $note = $this->sql('
            SELECT * FROM note WHERE id_membre=:id
        ', array(
            'id' => $idUser
        ));
        return $note->rowCount();
    }

    public function createNote($note) {
        $this->sql('INSERT INTO note VALUES(null, :note, :id_membre)', array(
            'note' => $note['note'],
            'id_membre' => $note['id_membre'],
        ));
        return $this->pdo()->lastInsertId('id_note');
    }

    public function sumNoteByIDMembre($id_membre) {
        $sumNoteByIDMembre = $this->sql('SELECT SUM(note) FROM `note` WHERE id_membre=:id_membre', array(
            'id_membre' => $id_membre
        ));
        return $sumNoteByIDMembre->fetch()['SUM(note)'];
    }

    public function countNoteByIDMembre($id_membre) {
        $sumNoteByIDMembre = $this->sql('SELECT COUNT(note) FROM `note` WHERE id_membre=:id_membre', array(
            'id_membre' => $id_membre
        ));
        return (int)$sumNoteByIDMembre->fetch()['COUNT(note)'];
    }

    public function countNote() {
        $sumNote = $this->sql('SELECT COUNT(note) FROM `note`');
        return (int)$sumNote->fetch()['COUNT(note)'];
    }
    public function sumNote() {
        $sumNote = $this->sql('SELECT SUM(note) FROM `note`');
        return $sumNote->fetch()['SUM(note)'];
    }
}
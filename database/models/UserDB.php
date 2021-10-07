<?php

require_once __DIR__ . "/../Database.php";

class UserDB extends Database
{
    public function fetchAll(): array
    {
        $user = $this->sql("SELECT * FROM membre");
        return $user->fetchAll();
    }

    public function fetchOne($param, $value)
    {
        $user = $this->sql("SELECT * FROM membre WHERE $param=:value", array(
            'value' => $value
        ));
        return $user->fetch();
    }


    public function deleteOne(int $id)
    {
        $this->sql("DELETE FROM membre WHERE id_membre=:id_membre", array(
            'id_membre' => $id
        ));

    }

    public function createOne($user)
    {
        $this->sql("INSERT INTO membre VALUES (NULL, :pseudo, :mdp, NULL, NULL, :telephone, :email, NULL, 0, NOW())", array(
            'pseudo' => $user['pseudo'],
            'mdp' => password_hash($user['mdp'], PASSWORD_ARGON2I),
            'telephone' => $user['telephone'],
            'email' => $user['email'],
        ));
//        return $this->fetchOne($this->pdo()->lastInsertId());
    }

    public function updateOneROLE_USER($user)
    {
        $this->sql("UPDATE membre SET status=:status WHERE id_membre=:id_membre", array(
            'status' => $user['status'],
            'id_membre' => $user['id_membre']
        ));
    }

    public function updateOne($user)
    {
        $this->sql("UPDATE membre SET pseudo=:pseudo, nom=:nom, prenom=:prenom, telephone=:telephone, email=:email, civilite=:civilite WHERE id_membre=:id_membre", array(
            'pseudo' => $user['pseudo'],
            'nom' => $user['nom'],
            'prenom' => $user['prenom'],
            'telephone' => $user['telephone'],
            'email' => $user['email'],
            'civilite' => $user['civilite'],
            'id_membre' => $user['id_membre']
        ));
    }

    public function updateUserWithPassword($user)
    {
        $this->sql("UPDATE membre SET pseudo=:pseudo, mdp=:mdp, nom=:nom, prenom=:prenom, telephone=:telephone, email=:email, civilite=:civilite WHERE id_membre=:id_membre", array(
            'pseudo' => $user['pseudo'],
            'mdp' => password_hash($user['mdp'], PASSWORD_ARGON2I),
            'nom' => $user['nom'],
            'prenom' => $user['prenom'],
            'telephone' => $user['telephone'],
            'email' => $user['email'],
            'civilite' => $user['civilite'],
            'id_membre' => $user['id_membre']
        ));
    }
}

return new UserDB();
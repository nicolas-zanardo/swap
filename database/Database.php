<?php

require_once __DIR__ . '/../env/Variable.php';

class Database extends Variable
{
    /**
     * Create Connection DATABASE SQL
     * @return PDOException|PDO
     */
    protected function pdo()
    {
        try {
            return new PDO($this->DNS, $this->DB_USER, $this->DB_PWD, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_PERSISTENT => true
            ]);
        } catch (PDOException $e) {
            return $e;
        }
    }

    /**
     * sql
     * @param string $request
     * @param array $params
     * @return PDOStatement
     */
    protected function sql(string $request, array $params = array()): PDOStatement {

        $statement = $this->pdo()->prepare($request);

        if(!empty($params)) {
            foreach($params as $key => $value) {
                $statement->bindValue($key, trim(htmlspecialchars($value)), PDO::PARAM_STR);
            }
        }
        $statement->execute();
        return $statement;
    }

}
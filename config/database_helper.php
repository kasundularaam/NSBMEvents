<?php

class DatabaseHelper
{
    private $dbHost;
    private $dsn;
    private $username;
    private $password;
    private $db;

    public function __construct()
    {
        $this->dbHost = $_SERVER['SERVER_NAME'];
        $this->dsn = "mysql:host=" . $this->dbHost . ";dbname=nsbm_events";
        $this->username = "berry";
        $this->password = "123456";
    }

    public function connect()
    {
        try {
            $this->db = new PDO($this->dsn, $this->username, $this->password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new $e;
        }
    }

    public function query($sql, $parameters = [])
    {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($parameters);
            return $stmt;
        } catch (PDOException $e) {
            // handle the exception
        }
    }

    public function close()
    {
        $this->db = null;
    }
}

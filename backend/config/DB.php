<?php

class DB
{
    private $server = "mysql:host=localhost;dbname=binzo;charset=utf8";
    private $user = "root";
    private $pass = "";
    private $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    );
    protected $connection;

    public function openConnection()
    {
        try
        {
            $this->connection = new PDO($this->server, $this->user, $this->pass, $this->options);
            return $this->connection;
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function closeConnection()
    {
        $this->connection = null;
    }
}

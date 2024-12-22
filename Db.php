<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'ledenadministratie';
    private $username = 'beheerderKeu';
    private $password = 'Q[.p0[2R8NMq.0rF';
    private $conn;

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
                                  $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
    public function disconnect(){
        $this->conn = null;
    }
}
?>

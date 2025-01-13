<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'membership_administration';
    private $username = 'adminCC';
    private $password = '3[jrprSIGnPcg31H';
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

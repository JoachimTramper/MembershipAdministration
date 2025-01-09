<?php

class LedenModel {
    private $db;

    //Constructor for database connection 
    public function __construct($db) {
        $this->db = $db;
    }
    //Function for updating account details (username and password)
    public function updateAccount($userId, $newUsername, $newPassword) {
        try {
            //Establish a connection to the database
            $conn = $this->db->connect();
            //Prepare the SQL query to update the username and password
            $stmt = $conn->prepare("UPDATE gebruikers SET gebruikersnaam = ?, wachtwoord = ? WHERE id = ?");
            //Execute the query with the new username and password (hashed)
            $stmt->execute([$newUsername, password_hash($newPassword, PASSWORD_DEFAULT), $userId]);
            return true;        //Successfully updated
        } catch (PDOException $e) {
            //If an error occurs, throw an exception with an error message
            return false;
        }
    }
    //Function to retrieve all members
    public function getAllLeden() {
        try {
            $conn = $this->db->connect();
            if ($conn) {
            } else {
                echo "Database connection failed!<br>";
            }
            //SQL query to retrieve members
            $sql = "SELECT fl.id, fl.naam, fa.adres, sl.soort_lid 
            FROM familieleden fl
            INNER JOIN families fa ON fl.familie_id = fa.id  
            INNER JOIN soorten_lid sl ON fl.soort_lid_id = sl.id
            ORDER BY fl.id ASC";
            $conn = $this->db->connect();  
            $stmt = $conn->prepare($sql);
            //Execute the query 
            $stmt->execute();
            //Fetch all results and store them in a variable
            $leden = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //If no members are found, display a message       
            if (count($leden) === 0) {
                echo "No members found!<br>";
            }                       
            //Return the results as an associative array
            return $leden;
        } catch (PDOException $e) {
            //Error handling for database issues
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    //Retrieves a specific member based on the ID
    public function getLidById($id) {
        $sql = "SELECT f.id, f.familie_id, f.naam, f.geboortedatum, f.soort_lid_id, g.gebruikersnaam, g.wachtwoord, g.rol, fam.adres
                FROM familieleden f
                JOIN gebruikers g ON f.id = g.familieleden_id
                JOIN families fam ON f.familie_id = fam.id
                WHERE f.id = :id";        
        try {       
            $conn = $this->db->connect();  
            $stmt = $conn->prepare($sql);
            //Bind the parameter 'id' to the query
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();  
            
            //Fetch the result (return the first row)
            return $stmt->fetch(PDO::FETCH_ASSOC);  //Retrieve the member's data as an associative array
        } catch (PDOException $e) {
            echo "Error retrieving the member: " . $e->getMessage();
        }
    } 
    //Retrieves all members for editing purposes
    public function getAllLedenBewerken() {
        //SQL query to retrieve members for editing, including role, address, and membership type
        $sql = "SELECT 
            f.id, f.naam, f.geboortedatum, f.soort_lid_id, g.gebruikersnaam, g.wachtwoord, g.rol, f.familie_id, fa.adres, sl.soort_lid, r.rol_soort
        FROM familieleden f 
        JOIN gebruikers g ON f.id = g.familieleden_id
        JOIN families fa ON f.familie_id = fa.id
        JOIN soorten_lid sl ON f.soort_lid_id = sl.id
        JOIN rol r ON g.rol = r.id
        ORDER BY f.id ASC"; 
        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            //Fetch all results and store them in a variable
            $leden = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //If no members are found, display a message 
            error_log("Members data: " . print_r($leden, true));    
            if (count($leden) === 0) {
                echo "No members found!<br>";
            }                       
            //Return the results as an associative array
            return $leden;
        } catch (PDOException $e) {
            throw new Exception("Error retrieving members: " . $e->getMessage());
        }
    }
    //Retrieves all family members, including any outstanding payments
    public function getFamilieledenMetOpenstaandeBetalingen($gebruiker_id) {
        try {
            $conn = $this->db->connect();
            //Retrieve the family_id of the user
            $query_familie_id = "SELECT familie_id FROM familieleden WHERE id = :gebruiker_id";
            $stmt_familie_id = $conn->prepare($query_familie_id);
            $stmt_familie_id->bindParam(':gebruiker_id', $gebruiker_id);
            $stmt_familie_id->execute();
            $familie_id = $stmt_familie_id->fetchColumn();
            
            //If no family_id is found, return an empty array
            if (!$familie_id) {
                return [];
            }
            //Retrieve all family members, including payments (if available)
            $query = "
                SELECT f.id, f.naam, c.bedrag, c.betaaldatum
                FROM familieleden f
                LEFT JOIN contributies c ON f.id = c.familielid_id AND c.betaaldatum IS NULL
                WHERE f.familie_id = :familie_id
            "; 
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':familie_id', $familie_id);
            $stmt->execute();
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error retrieving family members and outstanding payments: " . $e->getMessage();
        }
    }
    
}




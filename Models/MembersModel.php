<?php

class MembersModel {
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
            $stmt = $conn->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
            //Execute the query with the new username and password (hashed)
            $stmt->execute([$newUsername, password_hash($newPassword, PASSWORD_DEFAULT), $userId]);
            return true;        //Successfully updated
        } catch (PDOException $e) {
            //If an error occurs, throw an exception with an error message
            return false;
        }
    }
    //Function to retrieve all members
    public function getAllMembers() {
        try {
            $conn = $this->db->connect();
            if ($conn) {
            } else {
                echo "Database connection failed!<br>";
            }
            //SQL query to retrieve members
            $sql = "SELECT fm.id, fm.name, fa.address, mt.member_type 
            FROM family_members fm
            INNER JOIN families fa ON fm.family_id = fa.id  
            INNER JOIN member_types mt ON fm.member_type_id = mt.id
            ORDER BY fm.id ASC";
            $conn = $this->db->connect();  
            $stmt = $conn->prepare($sql);
            //Execute the query 
            $stmt->execute();
            //Fetch all results and store them in a variable
            $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //If no members are found, display a message       
            if (count($members) === 0) {
                echo "No members found!<br>";
            }                       
            //Return the results as an associative array
            return $members;
        } catch (PDOException $e) {
            //Error handling for database issues
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    //Retrieves a specific member based on the ID
    public function getMemberById($id) {
        $sql = "SELECT f.id, f.family_id, f.name, f.dob, f.member_type_id, u.username, u.password, u.role, fa.address
                FROM family_members f
                JOIN users u ON f.id = u.family_member_id
                JOIN families fa ON f.family_id = fa.id
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
    public function getAllMembersUpdate() {
        //SQL query to retrieve members for editing, including role, address, and membership type
        $sql = "SELECT 
            f.id, f.name, f.dob, f.member_type_id, u.username, u.password, u.role, f.family_id, fa.address, mt.member_type, r.role_type
        FROM family_members f 
        JOIN users u ON f.id = u.family_member_id
        JOIN families fa ON f.family_id = fa.id
        JOIN member_types mt ON f.member_type_id = mt.id
        JOIN roles r ON u.role = r.id
        ORDER BY f.id ASC"; 
        try {
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            //Fetch all results and store them in a variable
            $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //If no members are found, display a message 
            error_log("Members data: " . print_r($members, true));    
            if (count($members) === 0) {
                echo "No members found!<br>";
            }                       
            //Return the results as an associative array
            return $members;
        } catch (PDOException $e) {
            throw new Exception("Error retrieving members: " . $e->getMessage());
        }
    }
    //Retrieves all family members, including any outstanding payments
    public function getFamilyMembersOutstandingPayments($member_id) {
        try {
            $conn = $this->db->connect();
            //Retrieve the family_id of the user
            $query_family_id = "SELECT family_id FROM family_members WHERE id = :member_id";
            $stmt_family_id = $conn->prepare($query_family_id);
            $stmt_family_id->bindParam(':member_id', $member_id);
            $stmt_family_id->execute();
            $family_id = $stmt_family_id->fetchColumn();
            
            //If no family_id is found, return an empty array
            if (!$family_id) {
                return [];
            }
            //Retrieve all family members, including payments (if available)
            $query = "SELECT f.id, f.name, c.amount, c.payment_date
                FROM family_members f
                LEFT JOIN contributions c ON f.id = c.family_member_id AND c.payment_date IS NULL
                WHERE f.family_id = :family_id"; 
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':family_id', $family_id);
            $stmt->execute();
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error retrieving family members and outstanding payments: " . $e->getMessage();
        }
    }
    
}




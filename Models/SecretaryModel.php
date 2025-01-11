<?php

require_once dirname(__DIR__) . '/Db.php';

class SecretarisModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }
    //Add a member to the database
    public function voegLidToe($data) {
        try {
            $conn = $this->db->connect();

            //Determine the correct type_member_id
            $sqlSoortLid = "SELECT id 
                FROM member_types 
                WHERE TIMESTAMPDIFF(YEAR, ?, CURDATE()) BETWEEN age_from AND age_till";
            $stmt = $conn->prepare($sqlSoortLid);
            $stmt->execute([$data['geboortedatum']]);
            $soortLidId = $stmt->fetchColumn();

            //Add member to family_members
            $sql = "INSERT INTO family_members (family_id, name, dob, member_type_id) 
                VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$data['family_id'], $data['name'], $data['dob'], $soortLidId]);
    
            //Add user to users
            $sqlGebruiker = "INSERT INTO users (family_member_id, username, password, role) 
                VALUES (LAST_INSERT_ID(), ?, ?, ?)";
            $stmt = $conn->prepare($sqlGebruiker);
            $stmt->execute([
                $data['username'],
                password_hash($data['password'], PASSWORD_DEFAULT),
                $data['role']
            ]);   
            return $conn->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error adding a member: " . $e->getMessage());
        }
    }
    //Delete a member
    public function verwijderLid($id) {
        try {
            $conn = $this->db->connect();
            $sql = "DELETE FROM family_members WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new Exception("Error deleting a member: " . $e->getMessage());
        }
    }
    //Add a family
    public function voegFamilieToe($data) {
        try {
            $conn = $this->db->connect();
            $sql = "INSERT INTO families (name, address) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$data['family_name'], $data['address']]);
            return $conn->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error adding a family: " . $e->getMessage());
        }
    }
    //Delete a family
    public function deleteFamilie($familieId) {
        try {
            $conn = $this->db->connect(); 
            $sql = "DELETE FROM families WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$familieId]);
            //Check if a row has been deleted
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new Exception("Error deleting a family: " . $e->getMessage());
        }
    }
    //Retrieve all families
    public function getFamilies() {
        try {         
            $conn = $this->db->connect();
            $sql = "SELECT id, name, address FROM families"; 
            $stmt = $conn->prepare($sql);
            $stmt->execute(); 
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error retrieving families: " . $e->getMessage());
        }
    }
    //Retrieve all roles
    public function getRoles() {
        try {
            $conn = $this->db->connect();
            $sql = "SELECT id, role_type FROM roles";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error retrieving roles: " . $e->getMessage());
        }
    }
    //Update the details of a member
    public function updateLid($data) {
        try {
            //Update family members
            $conn = $this->db->connect();
            $sqlFamilie = "UPDATE family_members 
                           SET family_id = ?, name = ?, dob = ?
                           WHERE id = ?";
            $stmt = $conn->prepare($sqlFamilie);
            $stmt->execute([
                $data['family_id'],
                $data['name'],
                $data['dob'],
                $data['id'] 
            ]);  
            //Update users
            $sqlGebruiker = "UPDATE users
                             SET username = ?, password = ?, role = ? 
                             WHERE family_member_id = ?";
            $stmt = $conn->prepare($sqlGebruiker);
            $stmt->execute([
                $data['username'],
                password_hash($data['password'], PASSWORD_DEFAULT),
                $data['role'],
                $data['id'] 
            ]);
            return true; //Indicate that the update was successful
        } catch (PDOException $e) {
            throw new Exception("Fout bij het bewerken van een lid: " . $e->getMessage());
        }
    }
    
}

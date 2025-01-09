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
                FROM soorten_lid 
                WHERE TIMESTAMPDIFF(YEAR, ?, CURDATE()) BETWEEN leeftijd_vanaf AND leeftijd_tot";
            $stmt = $conn->prepare($sqlSoortLid);
            $stmt->execute([$data['geboortedatum']]);
            $soortLidId = $stmt->fetchColumn();

            //Add member to family_members
            $sql = "INSERT INTO familieleden (familie_id, naam, geboortedatum, soort_lid_id) 
                VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$data['familie_id'], $data['naam'], $data['geboortedatum'], $soortLidId]);
    
            //Add user to users
            $sqlGebruiker = "INSERT INTO gebruikers (familieleden_id, gebruikersnaam, wachtwoord, rol) 
                VALUES (LAST_INSERT_ID(), ?, ?, ?)";
            $stmt = $conn->prepare($sqlGebruiker);
            $stmt->execute([
                $data['gebruikersnaam'],
                password_hash($data['wachtwoord'], PASSWORD_DEFAULT),
                $data['rol']
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
            $sql = "DELETE FROM familieleden WHERE id = ?";
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
            $sql = "INSERT INTO families (naam, adres) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$data['familie_naam'], $data['adres']]);
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
            $sql = "SELECT id, naam, adres FROM families"; 
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
            $sql = "SELECT id, rol_soort FROM rol";
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
            $sqlFamilie = "UPDATE familieleden 
                           SET familie_id = ?, naam = ?, geboortedatum = ?
                           WHERE id = ?";
            $stmt = $conn->prepare($sqlFamilie);
            $stmt->execute([
                $data['familie_id'],
                $data['naam'],
                $data['geboortedatum'],
                $data['id'] 
            ]);  
            //Update users
            $sqlGebruiker = "UPDATE gebruikers 
                             SET gebruikersnaam = ?, wachtwoord = ?, rol = ? 
                             WHERE familieleden_id = ?";
            $stmt = $conn->prepare($sqlGebruiker);
            $stmt->execute([
                $data['gebruikersnaam'],
                password_hash($data['wachtwoord'], PASSWORD_DEFAULT),
                $data['rol'],
                $data['id'] 
            ]);
            return true; //Indicate that the update was successful
        } catch (PDOException $e) {
            throw new Exception("Fout bij het bewerken van een lid: " . $e->getMessage());
        }
    }
    
}

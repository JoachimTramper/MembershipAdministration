<?php

require_once dirname(__DIR__) . '/Db.php';

class SecretarisModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }
    //Voeg een lid toe aan de database.
    public function voegLidToe($data) {
        try {
            $conn = $this->db->connect();

            //Bepaal het juiste soort_lid_id.
            $sqlSoortLid = "SELECT id 
                FROM soorten_lid 
                WHERE TIMESTAMPDIFF(YEAR, ?, CURDATE()) BETWEEN leeftijd_vanaf AND leeftijd_tot";
            $stmt = $conn->prepare($sqlSoortLid);
            $stmt->execute([$data['geboortedatum']]);
            $soortLidId = $stmt->fetchColumn();

            //Voeg lid toe aan familieleden.
            $sql = "INSERT INTO familieleden (familie_id, naam, geboortedatum, soort_lid_id) 
                VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$data['familie_id'], $data['naam'], $data['geboortedatum'], $soortLidId]);
    
            //Voeg gebruiker toe aan gebruikers.
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
            throw new Exception("Fout bij het toevoegen van een lid: " . $e->getMessage());
        }
    }
    //Verwijder een lid.
    public function verwijderLid($id) {
        try {
            $conn = $this->db->connect();
            $sql = "DELETE FROM familieleden WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new Exception("Fout bij het verwijderen van een lid: " . $e->getMessage());
        }
    }
    //Voeg een familie toe.
    public function voegFamilieToe($data) {
        try {
            $conn = $this->db->connect();
            $sql = "INSERT INTO families (naam, adres) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$data['familie_naam'], $data['adres']]);
            return $conn->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Fout bij het toevoegen van een familie: " . $e->getMessage());
        }
    }
    //Verwijderen een familie.
    public function deleteFamilie($familieId) {
        try {
            $conn = $this->db->connect(); 
            $sql = "DELETE FROM families WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$familieId]);
            //Controleer of er een rij is verwijderd.
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new Exception("Fout bij het verwijderen van een familie: " . $e->getMessage());
        }
    }
    //Haal alle families op.
    public function getFamilies() {
        try {         
            $conn = $this->db->connect();
            $sql = "SELECT id, naam, adres FROM families"; // Pas de query aan als nodig
            $stmt = $conn->prepare($sql);
            $stmt->execute(); 
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Fout bij het ophalen van families: " . $e->getMessage());
        }
    }
    //Haal alle rollen op.
    public function getRoles() {
        try {
            $conn = $this->db->connect();
            $sql = "SELECT id, rol_soort FROM rol";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Fout bij het ophalen van rollen: " . $e->getMessage());
        }
    }
    //Werk de gegevens van een lid bij.
    public function updateLid($data) {
        try {
            //Familieleden bijwerken.
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
            //Gebruikers bijwerken.
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
            return true; // Geeft aan dat de update succesvol was
        } catch (PDOException $e) {
            throw new Exception("Fout bij het bewerken van een lid: " . $e->getMessage());
        }
    }
    
}

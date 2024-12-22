<?php

class LedenModel {
    private $db;

    //Constructor voor database verbinding. 
    public function __construct($db) {
        $this->db = $db;
    }
    //Functie voor het updaten van accountgegevens (gebruikersnaam en wachtwoord).
    public function updateAccount($userId, $newUsername, $newPassword) {
        try {
            //Maak verbinding met de database.
            $conn = $this->db->connect();
            //Bereid de SQL-query voor om de gebruikersnaam en het wachtwoord te updaten.
            $stmt = $conn->prepare("UPDATE gebruikers SET gebruikersnaam = ?, wachtwoord = ? WHERE id = ?");
            //Voer de query uit met de nieuwe gebruikersnaam en wachtwoord (gehasht).
            $stmt->execute([$newUsername, password_hash($newPassword, PASSWORD_DEFAULT), $userId]);
            return true;        //Succesvol bijgewerkt. 
        } catch (PDOException $e) {
            //Als er een fout optreedt, geef dan een foutmelding via een uitzondering.
            return false;
        }
    }
    //Functie om alle leden op te halen.
    public function getAllLeden() {
        try {
            $conn = $this->db->connect();
            if ($conn) {
            } else {
                echo "Databaseverbinding mislukt!<br>";
            }
            //SQL-query om leden op te halen. 
            $sql = "SELECT fl.id, fl.naam, fa.adres, sl.soort_lid 
            FROM familieleden fl
            INNER JOIN families fa ON fl.familie_id = fa.id  
            INNER JOIN soorten_lid sl ON fl.soort_lid_id = sl.id";
            $conn = $this->db->connect();  
            $stmt = $conn->prepare($sql);
            //Voer de query uit. 
            $stmt->execute();
            //Haal alle resultaten op en zet deze in een variabele. 
            $leden = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //Als er geen leden zijn, geef een melding.       
            if (count($leden) === 0) {
                echo "Geen leden gevonden!<br>";
            }                       
            //Geef de resultaten terug als een associatieve array.
            return $leden;
        } catch (PDOException $e) {
            //Foutafhandeling bij databaseproblemen.
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    //Haalt een specifiek lid op aan de hand van het ID.
    public function getLidById($id) {
        $sql = "SELECT f.id, f.familie_id, f.naam, f.geboortedatum, f.soort_lid_id, g.gebruikersnaam, g.wachtwoord, g.rol, fam.adres
                FROM familieleden f
                JOIN gebruikers g ON f.id = g.familieleden_id
                JOIN families fam ON f.familie_id = fam.id
                WHERE f.id = :id";        
        try {       
            $conn = $this->db->connect();  
            $stmt = $conn->prepare($sql);
            //Bind de parameter 'id' aan de query.
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();  
            
            //Haal het resultaat op (return de eerste rij).
            return $stmt->fetch(PDO::FETCH_ASSOC);  //Haal de gegevens van het lid als een associatieve array.
        } catch (PDOException $e) {
            echo "Fout bij het ophalen van het lid: " . $e->getMessage();
        }
    } 
    //Haalt alle leden op voor bewerkingsdoeleinden.
    public function getAllLedenBewerken() {
        //SQL-query om leden op te halen voor bewerking, inclusief rol, adres en soort lid.
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
            //Haal alle resultaten op en zet deze in een variabele.
            $leden = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //Als er geen leden zijn, geef een melding.   
            error_log("Leden data: " . print_r($leden, true));    
            if (count($leden) === 0) {
                echo "Geen leden gevonden!<br>";
            }                       
            // Geef de resultaten terug als een associatieve array.
            return $leden;
        } catch (PDOException $e) {
            throw new Exception("Fout bij het ophalen van leden: " . $e->getMessage());
        }
    }
    //Haalt alle familieleden inclusief eventuele openstaande betalingen.
    public function getFamilieledenMetOpenstaandeBetalingen($gebruiker_id) {
        try {
            $conn = $this->db->connect();
            //Haal het familie_id van de gebruiker op.
            $query_familie_id = "SELECT familie_id FROM familieleden WHERE id = :gebruiker_id";
            $stmt_familie_id = $conn->prepare($query_familie_id);
            $stmt_familie_id->bindParam(':gebruiker_id', $gebruiker_id);
            $stmt_familie_id->execute();
            $familie_id = $stmt_familie_id->fetchColumn();
            
            // Als er geen familie_id gevonden wordt, geef een lege array terug
            if (!$familie_id) {
                return [];
            }
            //Haal alle familieleden op, inclusief betalingen (indien aanwezig).
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
            echo "Fout bij ophalen familieleden en openstaande betalingen: " . $e->getMessage();
        }
    }
    
}




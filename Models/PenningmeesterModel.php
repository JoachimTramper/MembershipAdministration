<?php

require_once dirname(__DIR__) . '/Db.php';

class PenningmeesterModel {
    private $db;
    //Constructor om de databaseverbinding te initialiseren.
    public function __construct($db) {
        $this->db = $db;
    }
    //Haal alle contributies op.
    public function getAllContributies() {
        try {
            //Query in variabele zetten. 
            $sql = "SELECT c.id, c.bedrag, c.type, c.betaaldatum, c.boekjaar_id, c.aantekening, b.jaar AS boekjaar_jaar, f.naam AS familielid_naam
            FROM contributies c
            LEFT JOIN boekjaren b ON c.boekjaar_id = b.id
            LEFT JOIN familieleden f ON c.familielid_id = f.id";
            //Maak verbinding met de database.
            $conn = $this->db->connect();
            //Bereid de SQL-query voor om alle contributies op te halen. 
            $stmt = $conn->prepare($sql);
            //Voer de query uit.
            $stmt->execute();
            //Geef resultaten terug als array. 
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            //Foutmelding bij error.  
            throw new Exception("Error retrieving contributions: " . $e->getMessage());
        }
    }
    //Voeg een nieuwe contributie toe.
    public function addContributie($data) {
        try {
            $sql = "INSERT INTO contributies (familielid_id, bedrag, type, betaaldatum, boekjaar_id, aantekening) 
            VALUES (:familielid_id, :bedrag, :type, :betaaldatum, :boekjaar_id, :aantekening)";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            //Bind de parameters vanuit de array.
            $stmt->bindParam(':familielid_id', $data['familielid_id']);
            $stmt->bindParam(':bedrag', $data['bedrag']);
            $stmt->bindParam(':type', $data['type']);
            $stmt->bindParam(':betaaldatum', $data['betaaldatum']);
            $stmt->bindParam(':boekjaar_id', $data['boekjaar_id']);
            $stmt->bindParam(':aantekening', $data['aantekening']);
            
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error inserting contribution: " . $e->getMessage());
        }
    }

    //Update een contributie.
    public function updateContributie($data) {
        try {
            $sql = "UPDATE contributies SET familielid_id = :familielid_id, bedrag = :bedrag, type = :type, betaaldatum = :betaaldatum, boekjaar_id = :boekjaar_id, aantekening = :aantekening
                    WHERE id = :id";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            // Bind de parameters vanuit de array
            $stmt->bindParam(':familielid_id', $data['familielid_id']);
            $stmt->bindParam(':bedrag', $data['bedrag']);
            $stmt->bindParam(':type', $data['type']);
            $stmt->bindParam(':betaaldatum', $data['betaaldatum']);
            $stmt->bindParam(':boekjaar_id', $data['boekjaar_id']);
            $stmt->bindParam(':aantekening', $data['aantekening']);
            $stmt->bindParam(':id', $data['id']);
    
            $stmt->execute();
    
            return true; // Geeft aan dat de update succesvol was
        } catch (PDOException $e) {
            throw new Exception("Fout bij het bewerken van een contributie: " . $e->getMessage());
        }
    }
    
    //Verwijder een contributie.
    public function verwijderContributie($id) {
        $conn = $this->db->connect();
        $sql = "DELETE FROM contributies WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
        //Voer de query uit en check of deze succesvol is.
        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception("Er is een fout opgetreden bij het verwijderen van de contributie.");
        }
    }
    //Voeg een nieuw boekjaar toe.
    public function addNieuwBoekjaar() {
        try {
            $conn = $this->db->connect(); 
            //Haal het laatste jaar op.
            $sql = "SELECT MAX(jaar) AS laatste_jaar FROM boekjaren";
            $stmt = $conn->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $nieuwJaar = $result['laatste_jaar'] + 1; //Bereken het nieuwe jaar.
    
            //Voeg het nieuwe jaar toe aan de boekjaren tabel.
            $sql2 = "INSERT INTO boekjaren (jaar) VALUES (:jaar)";
            $insertStmt = $conn->prepare($sql2);
            $insertStmt->execute(['jaar' => $nieuwJaar]);
    
            //Return het nieuwe jaar zodat het verder gebruikt kan worden.
            return $nieuwJaar;  
        } catch (PDOException $e) {
            throw new Exception("Fout bij het toevoegen van een boekjaar: " . $e->getMessage());
        }
    }
    //Haal alle boekjaren op.
    public function getAllBoekjaren() {
        try {
            $sql = "SELECT id, jaar FROM boekjaren ORDER BY jaar DESC";
            $conn = $this->db->connect(); 
            $stmt = $conn->prepare($sql); 
            $stmt->execute();
            //Haal alle resultaten op en retourneer ze.
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error retrieving years: " . $e->getMessage());
        }
    }
    //Haal alle familieleden op.
    public function getAllFamilieleden() {
        try {
            $sql = "SELECT id, naam FROM familieleden";  
            $conn = $this->db->connect();          
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error retrieving family members: " . $e->getMessage());
        }
    }
    //Haal een specifieke contributie op basis van het ID.
    public function getContributieById($id) {
        try { 
            $sql = "SELECT c.id, c.familielid_id, c.bedrag, c.type, c.betaaldatum, c.boekjaar_id, c.aantekening, f.naam AS familielid_naam
                    FROM contributies c
                    LEFT JOIN familieleden f ON c.familielid_id = f.id
                    WHERE c.id = :id";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error retrieving contribution: " . $e->getMessage());
        }
    }
    //Haal contributies op per boekjaar, inclusief de naam van het familielid.
    public function getContributiesPerBoekjaar($boekjaar_id) {
        try {
            $sql = "SELECT c.*, f.naam 
                    FROM contributies c
                    LEFT JOIN familieleden f ON c.familielid_id = f.id
                    WHERE c.boekjaar_id = :boekjaar_id";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':boekjaar_id', $boekjaar_id);
            $stmt->execute();
    
            //Haal de contributies op.
            $contributies = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //Bereken de totalen voor inkomsten, uitgaven en belastingen.
            $inkomsten_totaal = 0;
            $uitgaven_totaal = 0;
            $belastingen_totaal = 0;
            //Bereken de totalen voor de verschillende types contributies.
            foreach ($contributies as $contributie) {
                if ($contributie['type'] == 'inkomsten') {
                    $inkomsten_totaal += $contributie['bedrag'];
                } elseif ($contributie['type'] == 'uitgaven') {
                    $uitgaven_totaal -= $contributie['bedrag']; // Negatief bedrag afhalen van totaal
                } elseif ($contributie['type'] == 'belastingen') {
                    $belastingen_totaal -= $contributie['bedrag']; // Belastingen als uitgaven
                }
            }              
            return [
                'contributies' => $contributies,
                'inkomsten_totaal' => $inkomsten_totaal,
                'uitgaven_totaal' => $uitgaven_totaal,
                'belastingen_totaal' => $belastingen_totaal,
                'totaal' => $inkomsten_totaal - $uitgaven_totaal - $belastingen_totaal
            ];   
        } catch (PDOException $e) {
            echo "Fout bij ophalen contributies per boekjaar: " . $e->getMessage();
        }
    }
        //Haal een boekjaar op op basis van id.
    public function getBoekjaarById($boekjaar_id) {
        $conn = $this->db->connect();
        $sql = "SELECT * FROM boekjaren WHERE id = :boekjaar_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':boekjaar_id', $boekjaar_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    //Haal de korting op voor een familielid op basis van hun soort_lid_id.
    public function getKorting($familielid_id) {
        //Haal het soort_lid_id op van het familielid.
        $conn = $this->db->connect();
        $sql = "SELECT soort_lid_id FROM familieleden WHERE id = :familielid_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':familielid_id', $familielid_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        //Als het familielid bestaat, haal de korting op basis van soort_lid_id.
        if ($result) {
            $soort_lid_id = $result['soort_lid_id'];   
            //Haal de korting op uit de soorten_lid tabel.
            $sql = "SELECT korting FROM soorten_lid WHERE id = :soort_lid_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':soort_lid_id', $soort_lid_id, PDO::PARAM_INT);
            $stmt->execute();
            $korting = $stmt->fetch(PDO::FETCH_ASSOC);
            return $korting ? $korting['korting'] : 0;
        }  
        return 0; //Return 0 als het familielid niet gevonden is.
    }

}
?>    
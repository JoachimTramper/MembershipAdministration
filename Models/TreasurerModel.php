<?php

require_once dirname(__DIR__) . '/Db.php';

class PenningmeesterModel {
    private $db;
    //Constructor to initialize the database connection
    public function __construct($db) {
        $this->db = $db;
    }
    //Retrieve all contributions
    public function getAllContributies() {
        try {
            //Store the query in a variable
            $sql = "SELECT c.id, c.bedrag, c.type, c.betaaldatum, c.boekjaar_id, c.aantekening, b.jaar AS boekjaar_jaar, f.naam AS familielid_naam
            FROM contributies c
            LEFT JOIN boekjaren b ON c.boekjaar_id = b.id
            LEFT JOIN familieleden f ON c.familielid_id = f.id";
            //Establish a connection to the database
            $conn = $this->db->connect();
            //Prepare the SQL query to retrieve all contributions
            $stmt = $conn->prepare($sql);
            //Execute the query
            $stmt->execute();
            //Return results as an array
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            //Error message on failure  
            throw new Exception("Error retrieving contributions: " . $e->getMessage());
        }
    }
    //Add a new contribution
    public function addContributie($data) {
        try {
            $sql = "INSERT INTO contributies (familielid_id, bedrag, type, betaaldatum, boekjaar_id, aantekening) 
            VALUES (:familielid_id, :bedrag, :type, :betaaldatum, :boekjaar_id, :aantekening)";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            //Bind the parameters from the array
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
    //Update a contribution
    public function updateContributie($data) {
        try {
            $sql = "UPDATE contributies SET familielid_id = :familielid_id, bedrag = :bedrag, type = :type, betaaldatum = :betaaldatum, boekjaar_id = :boekjaar_id, aantekening = :aantekening
                    WHERE id = :id";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            //Bind the parameters from the array
            $stmt->bindParam(':familielid_id', $data['familielid_id']);
            $stmt->bindParam(':bedrag', $data['bedrag']);
            $stmt->bindParam(':type', $data['type']);
            $stmt->bindParam(':betaaldatum', $data['betaaldatum']);
            $stmt->bindParam(':boekjaar_id', $data['boekjaar_id']);
            $stmt->bindParam(':aantekening', $data['aantekening']);
            $stmt->bindParam(':id', $data['id']);
    
            $stmt->execute();
    
            return true; //Indicates that the update was successful
        } catch (PDOException $e) {
            throw new Exception("Fout bij het bewerken van een contributie: " . $e->getMessage());
        }
    }
    //Delete a contribution
    public function verwijderContributie($id) {
        $conn = $this->db->connect();
        $sql = "DELETE FROM contributies WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
        //Execute the query and check if it was successful
        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception("An error occurred while deleting the contribution.");
        }
    }
    //Add a new fiscal year
    public function addNieuwBoekjaar() {
        try {
            $conn = $this->db->connect(); 
            //Retrieve the last year
            $sql = "SELECT MAX(jaar) AS laatste_jaar FROM boekjaren";
            $stmt = $conn->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $nieuwJaar = $result['laatste_jaar'] + 1; //Calculate the new fiscal year
    
            //Add the new fiscal year to the fiscal_years table
            $sql2 = "INSERT INTO boekjaren (jaar) VALUES (:jaar)";
            $insertStmt = $conn->prepare($sql2);
            $insertStmt->execute(['jaar' => $nieuwJaar]);
    
            //Return the new fiscal year so it can be used
            return $nieuwJaar;  
        } catch (PDOException $e) {
            throw new Exception("Error adding a fiscal year: " . $e->getMessage());
        }
    }
    //Retrieve all fiscal years
    public function getAllBoekjaren() {
        try {
            $sql = "SELECT id, jaar FROM boekjaren ORDER BY jaar DESC";
            $conn = $this->db->connect(); 
            $stmt = $conn->prepare($sql); 
            $stmt->execute();
            //Retrieve all results and return them
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error retrieving years: " . $e->getMessage());
        }
    }
    //Retrieve all family members
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
    //Retrieve a specific contribution based on the ID
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
    //Retrieve contributions per fiscal year, including the name of the family member
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
    
            //Retrieve the contributions
            $contributies = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //Calculate the totals for income, expenses, and taxes
            $inkomsten_totaal = 0;
            $uitgaven_totaal = 0;
            $belastingen_totaal = 0;
            //Calculate the totals for the different types of contributions
            foreach ($contributies as $contributie) {
                if ($contributie['type'] == 'income') {
                    $inkomsten_totaal += $contributie['bedrag'];
                } elseif ($contributie['type'] == 'expenses') {
                    $uitgaven_totaal -= $contributie['bedrag']; //Subtract the negative amount from the total
                } elseif ($contributie['type'] == 'taxes') {
                    $belastingen_totaal -= $contributie['bedrag']; //Taxes as expenses
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
            echo "Error retrieving contributions per fiscal year: " . $e->getMessage();
        }
    }
        //Retrieve a fiscal year based on the ID
    public function getBoekjaarById($boekjaar_id) {
        $conn = $this->db->connect();
        $sql = "SELECT * FROM boekjaren WHERE id = :boekjaar_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':boekjaar_id', $boekjaar_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    //Retrieve the discount for a family member based on their type_member_id
    public function getKorting($familielid_id) {
        //Retrieve the type_member_id of the family member
        $conn = $this->db->connect();
        $sql = "SELECT soort_lid_id FROM familieleden WHERE id = :familielid_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':familielid_id', $familielid_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        //If the family member exists, retrieve the discount based on the type_member_id
        if ($result) {
            $soort_lid_id = $result['soort_lid_id'];   
            //Retrieve the discount from the type_member table
            $sql = "SELECT korting FROM soorten_lid WHERE id = :soort_lid_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':soort_lid_id', $soort_lid_id, PDO::PARAM_INT);
            $stmt->execute();
            $korting = $stmt->fetch(PDO::FETCH_ASSOC);
            return $korting ? $korting['korting'] : 0;
        }  
        return 0; //Return 0 if the family member is not found
    }

}
?>    
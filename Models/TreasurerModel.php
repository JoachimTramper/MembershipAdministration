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
            $sql = "SELECT c.id, c.amount, c.type, c.payment_date, c.fiscal_year_id, c.note, fb.year AS fiscal_year, f.name AS family_member_name
            FROM contributions c
            LEFT JOIN fiscal_years fb ON c.fiscal_year_id = fb.id
            LEFT JOIN family_members f ON c.family_member_id = f.id";
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
            $sql = "INSERT INTO contributions (family_member_id, amount, type, payment_date, fiscal_year_id, note) 
            VALUES (:family_member_id, :amount, :type, :payment_date, :fiscal_year_id, :note)";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            //Bind the parameters from the array
            $stmt->bindParam(':family_member_id', $data['family_member_id']);
            $stmt->bindParam(':amount', $data['amount']);
            $stmt->bindParam(':type', $data['type']);
            $stmt->bindParam(':payment_date', $data['payment_date']);
            $stmt->bindParam(':fiscal_year_id', $data['fiscal_year_id']);
            $stmt->bindParam(':note', $data['note']);
            
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error inserting contribution: " . $e->getMessage());
        }
    }
    //Update a contribution
    public function updateContributie($data) {
        try {
            $sql = "UPDATE contributions SET family_member_id = :family_member_id, amount = :amount, type = :type, payment_date = :payment_date, fiscal_year_id = :fiscal_year_id, note = :note
                    WHERE id = :id";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            //Bind the parameters from the array
            $stmt->bindParam(':family_member_id', $data['family_member_id']);
            $stmt->bindParam(':amount', $data['amount']);
            $stmt->bindParam(':type', $data['type']);
            $stmt->bindParam(':payment_date', $data['payment_date']);
            $stmt->bindParam(':fiscal_year_id', $data['fiscal_year_id']);
            $stmt->bindParam(':note', $data['note']);
            $stmt->bindParam(':id', $data['id']);
    
            $stmt->execute();
    
            return true; //Indicates that the update was successful
        } catch (PDOException $e) {
            throw new Exception("Error editing a contribution: " . $e->getMessage());
        }
    }
    //Delete a contribution
    public function verwijderContributie($id) {
        $conn = $this->db->connect();
        $sql = "DELETE FROM contributions WHERE id = :id";
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
            $sql = "SELECT MAX(year) AS last_year FROM fiscal_years";
            $stmt = $conn->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $nieuwJaar = $result['last_year'] + 1; //Calculate the new fiscal year
    
            //Add the new fiscal year to the fiscal_years table
            $sql2 = "INSERT INTO fiscal_years (year) VALUES (:year)";
            $insertStmt = $conn->prepare($sql2);
            $insertStmt->execute(['year' => $nieuwJaar]);
    
            //Return the new fiscal year so it can be used
            return $nieuwJaar;  
        } catch (PDOException $e) {
            throw new Exception("Error adding a fiscal year: " . $e->getMessage());
        }
    }
    //Retrieve all fiscal years
    public function getAllBoekjaren() {
        try {
            $sql = "SELECT id, year FROM fiscal_years ORDER BY year DESC";
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
            $sql = "SELECT id, name FROM family_members";  
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
            $sql = "SELECT c.id, c.family_member_id, c.amount, c.type, c.payment_date, c.fiscal_year_id, c.note, f.name AS family_member_name
                    FROM contributions c
                    LEFT JOIN family_members f ON c.family_member_id = f.id
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
            $sql = "SELECT c.*, f.name 
                    FROM contributions c
                    LEFT JOIN family_members f ON c.family_member_id = f.id
                    WHERE c.fiscal_year_id = :fiscal_year_id";
            $conn = $this->db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':fiscal_year_id', $boekjaar_id);
            $stmt->execute();
    
            //Retrieve the contributions
            $contributies = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //Calculate the totals for income, expenses, and taxes
            $income_total = 0;
            $expenses_total = 0;
            $taxes_total = 0;
            //Calculate the totals for the different types of contributions
            foreach ($contributies as $contributie) {
                if ($contributie['type'] == 'income') {
                    $income_total += $contributie['amount'];
                } elseif ($contributie['type'] == 'expenses') {
                    $expenses_total -= $contributie['amount']; //Subtract the negative amount from the total
                } elseif ($contributie['type'] == 'taxes') {
                    $taxes_total -= $contributie['amount']; //Taxes as expenses
                }
            }              
            return [
                'contributions' => $contributies,
                'income_total' => $income_total,
                'expenses_total' => $expenses_total,
                'taxes_total' => $taxes_total,
                'total' => $income_total - $expenses_total - $taxes_total
            ];   
        } catch (PDOException $e) {
            echo "Error retrieving contributions per fiscal year: " . $e->getMessage();
        }
    }
        //Retrieve a fiscal year based on the ID
    public function getBoekjaarById($boekjaar_id) {
        $conn = $this->db->connect();
        $sql = "SELECT * FROM fiscal_years WHERE id = :fiscal_year_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':fiscal_year_id', $boekjaar_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    //Retrieve the discount for a family member based on their type_member_id
    public function getKorting($familielid_id) {
        //Retrieve the type_member_id of the family member
        $conn = $this->db->connect();
        $sql = "SELECT member_type_id FROM family_members WHERE id = :family_member_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':family_member_id', $familielid_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        //If the family member exists, retrieve the discount based on the type_member_id
        if ($result) {
            $soort_lid_id = $result['member_type_id'];   
            //Retrieve the discount from the type_member table
            $sql = "SELECT discount FROM member_types WHERE id = :member_type_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':member_type_id', $soort_lid_id, PDO::PARAM_INT);
            $stmt->execute();
            $korting = $stmt->fetch(PDO::FETCH_ASSOC);
            return $korting ? $korting['discount'] : 0;
        }  
        return 0; //Return 0 if the family member is not found
    }

}
?>    
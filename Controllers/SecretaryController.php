<?php

require_once dirname(__DIR__) . '/Models/SecretaryModel.php';
require_once dirname(__DIR__) . '/Models/MembersModel.php';
require_once dirname(__DIR__) . '/Db.php';

class SecretarisController {
    private $secretarisModel;
    private $ledenModel;

    public function __construct() {
        $db = new Database();
        $this->secretarisModel = new SecretarisModel($db);
        $this->ledenModel = new LedenModel($db);
    }

    //Add a member
    public function voegLidToe($data) {
        try {
            //Call the function in the model 
            $this->secretarisModel->voegLidToe($data);
            header("Location: index.php?page=member_management");
            exit;
        } catch (Exception $e) {
            echo "Fout: " . $e->getMessage();
        }
    }
    //Delete a member
    public function verwijderLid($id) {
        try {
            $this->secretarisModel->verwijderLid($id);
            header("Location: index.php?page=member_management");
            exit;
        } catch (Exception $e) {
            echo "Fout: " . $e->getMessage();
        }
    }
    //Add a family
    public function voegFamilieToe($data) {
        try {
            $this->secretarisModel->voegFamilieToe($data);
            header("Location: index.php?page=member_management");
            exit;
        } catch (Exception $e) {
            echo "Fout: " . $e->getMessage();
        }
    }
    //Delete a family
    public function verwijderFamilie($familieId) {
        //Call the function in the model and assign it to a variable 
        $result = $this->secretarisModel->deleteFamilie($familieId); 
        if ($result) {
            //Redirect to the edit members page after successful deletion (refresh)
            header("Location: index.php?page=member_management");
            exit;
        } else {
            echo "An error occurred while deleting the family.";
        }
    }    
    //Retrieve all families
    public function getFamilies() {
        try {
            $families = $this->secretarisModel->getFamilies(); 
            return $families;  //Return the list of families
        } catch (Exception $e) {
            die("Error while retrieving families: " . $e->getMessage());
        }
    }
    //Retrieve all roles
    public function getRoles() {
        try {
            $roles = $this->secretarisModel->getRoles();
            return $roles;
        } catch (Exception $e) {
            die("Error while retrieving roles: " . $e->getMessage());
        }
    //Edit a member
    }public function bewerkLid($id, $data = null) {
        //Set data to null for both processing a POST request and retrieving data
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $data) {
            $data['id'] = $id;
            //Validation of fields
            $requiredFields = ['naam', 'adres', 'geboortedatum', 'gebruikersnaam', 'wachtwoord', 'familie_id', 'rol'];
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    throw new Exception("Field '$field' is mandatory!");
                }
            }
            //Updating data
            $this->secretarisModel->updateLid($data);
            header("Location: index.php?page=member_management"); //Redirect on success
            exit;
        }    
        //Retrieve current data
        $lid = $this->ledenModel->getLidById($id);
        if (!$lid) {
            throw new Exception("Member not found!");
        } 
        include 'views/update_member.php'; //Display the form with existing data
    }
    
}                



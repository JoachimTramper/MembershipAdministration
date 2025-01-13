<?php

require_once dirname(__DIR__) . '/Models/SecretaryModel.php';
require_once dirname(__DIR__) . '/Models/MembersModel.php';
require_once dirname(__DIR__) . '/Db.php';

class SecretaryController {
    private $secretaryModel;
    private $membersModel;

    public function __construct() {
        $db = new Database();
        $this->secretaryModel = new SecretaryModel($db);
        $this->membersModel = new MembersModel($db);
    }

    //Add a member
    public function addMember($data) {
        try {
            //Call the function in the model 
            $this->secretaryModel->addMember($data);
            header("Location: index.php?page=member_management");
            exit;
        } catch (Exception $e) {
            echo "Error adding member: " . $e->getMessage();
        }
    }
    //Delete a member
    public function deleteMember($id) {
        try {
            $this->secretaryModel->deleteMember($id);
            header("Location: index.php?page=member_management");
            exit;
        } catch (Exception $e) {
            echo "Error deleting member: " . $e->getMessage();
        }
    }
    //Add a family
    public function addFamily($data) {
        try {
            $this->secretaryModel->addFamily($data);
            header("Location: index.php?page=member_management");
            exit;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    //Delete a family
    public function deleteFamily($familyId) {
        //Call the function in the model and assign it to a variable 
        $result = $this->secretaryModel->deleteFamily($familyId); 
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
            $families = $this->secretaryModel->getFamilies(); 
            return $families;  //Return the list of families
        } catch (Exception $e) {
            die("Error retrieving families: " . $e->getMessage());
        }
    }
    //Retrieve all roles
    public function getRoles() {
        try {
            $roles = $this->secretaryModel->getRoles();
            return $roles;
        } catch (Exception $e) {
            die("Error retrieving roles: " . $e->getMessage());
        }
    //Edit a member
    }public function updateMember($id, $data = null) {
        //Set data to null for both processing a POST request and retrieving data
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $data) {
            $data['id'] = $id;
            //Validation of fields
            $requiredFields = ['name', 'address', 'dob', 'username', 'password', 'family_id', 'role'];
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    throw new Exception("Field '$field' is mandatory!");
                }
            }
            //Updating data
            $this->secretaryModel->updateMember($data);
            header("Location: index.php?page=member_management"); //Redirect on success
            exit;
        }    
        //Retrieve current data
        $member = $this->membersModel->getMemberById($id);
        if (!$member) {
            throw new Exception("Member not found!");
        } 
        include 'views/update_member.php'; //Display the form with existing data
    }
    
}                



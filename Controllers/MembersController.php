<?php

//Include models and database 
require_once dirname(__DIR__) . '/Models/MembersModel.php';
require_once dirname(__DIR__) . '/Models/SecretaryModel.php';
require_once dirname(__DIR__) . '/Db.php';

class MembersController {
    private $membersModel;
    private $secretaryModel;

    public function __construct() {
        $db = new Database();
        $this->membersModel = new MembersModel($db);
        $this->secretaryModel = new SecretaryModel($db);
    }
    //Update account
    public function updateAccount() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_account'])) {
            $newUsername = $_POST['username'];
            $newPassword = $_POST['password'];
            $userId = $_SESSION['user_id'];
    
            //Update account details via the model
            $updateSuccess = $this->membersModel->updateAccount($userId, $newUsername, $newPassword);
    
            if ($updateSuccess) {
                $_SESSION['username'] = $newUsername; //Update the session
                header("Location: index.php?page=dashboard");
                exit;
            } else {
                //Error message if account update fails
                echo "<p>An error occurred while updating the account.</p>";
            }
        }
    }

    //Members overview
    public function showMembersOverview() {   
    
        try {
            //Retrieve the data of all members
            $members = $this->membersModel->getAllMembers();
            
            //Display the view for the member overview
            include_once __DIR__ . '/../views/members_overview.php';
            //If there are no members, display a message in the view
            if (empty($members)) {
                $errorMessage = "No member data available.";
            } else {
                $errorMessage = null;
            } 
            //Display an error message when retrieving members
        } catch (Exception $e) {
            echo "An error occurred while retrieving the members: " . $e->getMessage();
        }
    }
    //Retrieve member details
    public function showMember($id) {
        $member = $this->membersModel->getMemberById($id);
        return $member;    //Return the member to be used in the view
    }
    //Retrieve all members
    public function getAllMembers() {
        return $this->membersModel->getAllMembers();
    }
    
    //Retrieve all members for editing
    public function getAllMembersUpdate() {
        try {
            //Retrieve the data of all members 
            $members = $this->membersModel->getAllMembersUpdate();
            $roles = $this->secretaryModel->getRoles();
            $families = $this->secretaryModel->getFamilies(); 

            //Display the view for editing members
            include_once __DIR__ . '/../views/member_management.php';
            //If there are no members, display a message in the view
            if (empty($members)) {
                $errorMessage = "No member data available.";
            } else {
                $errorMessage = null;
            } 
            //Display an error message when retrieving members
        } catch (Exception $e) {
            echo "An error occurred while retrieving the members: " . $e->getMessage();
        }
    }
    //Dashboard view for the user
    public function showDashboard() {
        //Retrieve the user_id, username, and role from the session.
        $user_id = $_SESSION['user_id'];
        $user = $_SESSION['username'];
        $role = $_SESSION['role'];
    
        //Retrieve the family members with outstanding payments (including members without payments)
        $members = $this->membersModel->getFamilyMembersOutstandingPayments($user_id);
    
        //Check if any family members were found
        if (!empty($members)) {
            //Calculate the total of outstanding payments
            $total_outstanding_payments = 0;
            foreach ($members as $member) {
                if ($member['amount'] !== null) {
                    $total_outstanding_payments += $member['amount'];
                }
            }
        } else {
            //No family members or payments found
            $members = []; //Set $members to an empty array to prevent errors in the view
            $total_outstanding_payments = 0;
        }
    
        //Load the dashboard view
        include_once __DIR__ . '/../views/dashboard.php';
    }

}

?>

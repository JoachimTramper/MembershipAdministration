<?php

//Include models and database 
require_once dirname(__DIR__) . '/Models/MembersModel.php';
require_once dirname(__DIR__) . '/Models/SecretaryModel.php';
require_once dirname(__DIR__) . '/Db.php';

class LedenController {
    private $ledenModel;
    private $secretarisModel;

    public function __construct() {
        $db = new Database();
        $this->ledenModel = new LedenModel($db);
        $this->secretarisModel = new SecretarisModel($db);
    }
    //Update account
    public function vernieuwAccount() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_account'])) {
            $newUsername = $_POST['gebruikersnaam'];
            $newPassword = $_POST['wachtwoord'];
            $userId = $_SESSION['user_id'];
    
            //Update account details via the model
            $updateSuccess = $this->ledenModel->updateAccount($userId, $newUsername, $newPassword);
    
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
    public function showLedenOverzicht() {   
    
        try {
            //Retrieve the data of all members
            $leden = $this->ledenModel->getAllLeden();
            
            //Display the view for the member overview
            include_once __DIR__ . '/../views/members_overview.php';
            //If there are no members, display a message in the view
            if (empty($leden)) {
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
    public function showLid($id) {
        $lid = $this->ledenModel->getLidById($id);
        return $lid;    //Return the member to be used in the view
    }
    //Retrieve all members
    public function getAllLeden() {
        return $this->ledenModel->getAllLeden();
    }
    
    //Retrieve all members for editing
    public function getAllLedenBewerken() {
        try {
            //Retrieve the data of all members 
            $leden = $this->ledenModel->getAllLedenBewerken();
            $roles = $this->secretarisModel->getRoles();
            $families = $this->secretarisModel->getFamilies(); 

            //Display the view for editing members
            include_once __DIR__ . '/../views/member_management.php';
            //If there are no members, display a message in the view
            if (empty($leden)) {
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
        $gebruiker = $_SESSION['username'];
        $rol = $_SESSION['role'];
    
        //Retrieve the family members with outstanding payments (including members without payments)
        $leden = $this->ledenModel->getFamilieledenMetOpenstaandeBetalingen($user_id);
    
        //Check if any family members were found
        if (!empty($leden)) {
            //Calculate the total of outstanding payments
            $totaal_openstaande_betalingen = 0;
            foreach ($leden as $lid) {
                if ($lid['bedrag'] !== null) {
                    $totaal_openstaande_betalingen += $lid['bedrag'];
                }
            }
        } else {
            //No family members or payments found
            $leden = []; //Set $leden to an empty array to prevent errors in the view
            $totaal_openstaande_betalingen = 0;
        }
    
        //Load the dashboard view
        include_once __DIR__ . '/../views/dashboard.php';
    }

}

?>

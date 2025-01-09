<?php
//Start ob to avoid issues with redirection
ob_start();
require_once dirname(__DIR__) . '/Models/TreasurerModel.php';
require_once dirname(__DIR__) . '/Db.php';

class PenningmeesterController {
    private $penningmeesterModel;

    public function __construct() {
        $db = new Database();
        $this->penningmeesterModel = new PenningmeesterModel($db);
    }

    //Display the overview of contributions
    public function showContributiesOverzicht() {
        try {
            //Retrieve contributions, fiscal years, and family members
            $contributies = $this->penningmeesterModel->getAllContributies();
            $boekjaren = $this->penningmeesterModel->getAllBoekjaren(); 
            $familieleden = $this->penningmeesterModel->getAllFamilieleden();
            //Display the view
            include_once __DIR__ . '/../views/contributions_overview.php';
        } catch (Exception $e) {
            echo "An error occurred while retrieving contributions, fiscal years, or family members: " . $e->getMessage();
        }
    }
    //Add a new contribution
    public function voegContributieToe() {
        try {
            //Retrieve the data from the form and store it in an array 
            $data = [
                'familielid_id' => empty($_POST['familielid_id']) ? NULL : $_POST['familielid_id'],
                'bedrag' => $_POST['bedrag'],
                'type' => $_POST['type'],
                'betaaldatum' => empty($_POST['betaaldatum']) ? NULL : $_POST['betaaldatum'],
                'boekjaar_id' => $_POST['boekjaar_id'],
                'aantekening' => $_POST['aantekening']
            ];       
            //Calculate the amount if the family member has a discount and no amount has been entered
            if ($data['familielid_id'] && empty($data['bedrag'])) {
                //Retrieve the discount for the family member
                $kortingPercentage = $this->penningmeesterModel->getKorting($data['familielid_id']);    
                //The default amount is 100 euros; apply the discount
                $data['bedrag'] = 100 * (1 - $kortingPercentage / 100);
            }     
            //Add the contribution through the model
            $this->penningmeesterModel->addContributie($data);     
            //Redirect to the overview page
            header("Location: index.php?page=contributions_overview&id");
            exit;       
        } catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage();
        }
    }    
    //Delete a contribution
    public function verwijderContributie($id) {
        try {
            //Delete the contribution through the model
            $this->penningmeesterModel->verwijderContributie($id);
            //Redirect to the contributions overview (refresh)
            header("Location: index.php?page=contributions_overview");
            ob_end_flush();     //Flush the output buffer after the header (not strictly necessary)
            exit;
        } catch (Exception $e) {
            echo "Error while deleting the contribution: " . $e->getMessage();
        }
    }
    //Update an existing contribution
    public function wijzigContributie($id) {
        //Retrieve the current data of the contribution
        $contributie = $this->penningmeesterModel->getContributieById($id);   
        if (!$contributie) {
            //If no contribution is found, display an error message
            echo "Contribution not found!";
            return;
        }     
        //If it is a POST request, update the contribution
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Create an array with the data from the POST request
            $data = [
                'id' => $_POST['id'],
                'familielid_id' => $_POST['familielid_id'] ? $_POST['familielid_id'] : NULL, //Set NULL if no family_member_id is provided
                'bedrag' => $_POST['bedrag'],
                'type' => $_POST['type'],
                'betaaldatum' => $_POST['betaaldatum'] ? $_POST['betaaldatum'] : NULL, //Set NULL if no payment date is provided
                'boekjaar_id' => $_POST['boekjaar_id'],
                'aantekening' => $_POST['aantekening']
            ];  
            //Update the contribution through the model
            $success = $this->penningmeesterModel->updateContributie($data);     
            if ($success) {
                //Redirect to the contributions overview if the update is successful
                header("Location: index.php?page=contributions_overview");
                ob_end_flush();     //Flush the output buffer after the header (not strictly necessary)
                exit;
            } else {
                //Display an error message if the update failed
                echo "Error while updating the contribution";
            }
        }       
        //Retrieve the fiscal years for the form
        $boekjaren = $this->penningmeesterModel->getAllBoekjaren();      
        //Display the edited form with the current data
        include 'views/update_contribution.php';
    }        
    //Add a new fiscal year
    public function voegNieuwBoekjaarToe() {
        try {
            //Add the new fiscal year through the model
            $nieuwJaar = $this->penningmeesterModel->addNieuwBoekjaar(); 
            
            //Redirect to the contributions overview (refresh)
            header("Location: index.php?page=contributions_overview");
            ob_end_flush();     //Flush the output buffer after the header (not strictly necessary)
            exit;             
        } catch (Exception $e) {
            echo "Error while adding a new fiscal year: " . $e->getMessage();
        }
    }
    //Display contributions per fiscal year
    public function showContributiesPerBoekjaar($boekjaar_id = null) {
        try {
            //Check if a fiscal_year_id is provided. If not, try to retrieve it from the query string
            if ($boekjaar_id === null) {
                $boekjaar_id = isset($_GET['boekjaar_id']) ? intval($_GET['boekjaar_id']) : null;
            }
                //Retrieve contributions per fiscal year
            if ($boekjaar_id) {
                $contributiesData = $this->penningmeesterModel->getContributiesPerBoekjaar($boekjaar_id);
                $boekjaar = $this->penningmeesterModel->getBoekjaarById($boekjaar_id);
                $boekjaren = $this->penningmeesterModel->getAllBoekjaren();
                //Retrieve the contributions and totals from the result
                $contributies = $contributiesData['contributies'];
                $inkomsten_totaal = $contributiesData['inkomsten_totaal'];
                $uitgaven_totaal = $contributiesData['uitgaven_totaal'];
                $belastingen_totaal = $contributiesData['belastingen_totaal'];
                $totaal = $contributiesData['totaal'];                
            } else {
                //Display an error if no fiscal year is selected
                throw new Exception("No valid fiscal year selected.");
            }  
            //Display the view for the selected fiscal year
            include_once __DIR__ . '/../views/year_overview.php';
        } catch (Exception $e) {
            echo "An error occurred while retrieving the data: " . $e->getMessage();
        }
    }
    
    
}

?>
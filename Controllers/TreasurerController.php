<?php
//Start ob to avoid issues with redirection
ob_start();
require_once dirname(__DIR__) . '/Models/TreasurerModel.php';
require_once dirname(__DIR__) . '/Db.php';

class TreasurerController {
    private $treasurerModel;

    public function __construct() {
        $db = new Database();
        $this->treasurerModel = new TreasurerModel($db);
    }

    //Display the overview of contributions
    public function showContributionsOverview() {
        try {
            //Retrieve contributions, fiscal years, and family members
            $contributions = $this->treasurerModel->getAllContributions();
            $fiscal_years = $this->treasurerModel->getAllFiscalYears(); 
            $family_members = $this->treasurerModel->getAllFamilyMembers();
            //Display the view
            include_once __DIR__ . '/../views/contributions_overview.php';
        } catch (Exception $e) {
            echo "An error occurred while retrieving contributions, fiscal years, or family members: " . $e->getMessage();
        }
    }
    //Add a new contribution
    public function addContribution() {
        try {
            //Retrieve the data from the form and store it in an array 
            $data = [
                'family_member_id' => empty($_POST['family_member_id']) ? NULL : $_POST['family_member_id'],
                'amount' => $_POST['amount'],
                'type' => $_POST['type'],
                'payment_date' => empty($_POST['payment_date']) ? NULL : $_POST['payment_date'],
                'fiscal_year_id' => $_POST['fiscal_year_id'],
                'note' => $_POST['note']
            ];       
            //Calculate the amount if the family member has a discount and no amount has been entered
            if ($data['family_member_id'] && empty($data['amount'])) {
                //Retrieve the discount for the family member
                $discountPercentage = $this->treasurerModel->getDiscount($data['family_member_id']);    
                //The default amount is 100 EUR; apply the discount
                $data['amount'] = 100 * (1 - $discountPercentage / 100);
            }     
            //Add the contribution through the model
            $this->treasurerModel->addContribution($data);     
            //Redirect to the overview page
            header("Location: index.php?page=contributions_overview&id");
            exit;       
        } catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage();
        }
    }    
    //Delete a contribution
    public function deleteContribution($id) {
        try {
            //Delete the contribution through the model
            $this->treasurerModel->deleteContribution($id);
            //Redirect to the contributions overview (refresh)
            header("Location: index.php?page=contributions_overview");
            ob_end_flush();     //Flush the output buffer after the header (not strictly necessary)
            exit;
        } catch (Exception $e) {
            echo "Error deleting the contribution: " . $e->getMessage();
        }
    }
    //Update an existing contribution
    public function updateContribution($id) {
        //Retrieve the current data of the contribution
        $contribution = $this->treasurerModel->getContributionById($id);   
        if (!$contribution) {
            //If no contribution is found, display an error message
            echo "Contribution not found!";
            return;
        }     
        //If it is a POST request, update the contribution
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Create an array with the data from the POST request
            $data = [
                'id' => $_POST['id'],
                'family_member_id' => $_POST['family_member_id'] ? $_POST['family_member_id'] : NULL, //Set NULL if no family_member_id is provided
                'amount' => $_POST['amount'],
                'type' => $_POST['type'],
                'payment_date' => $_POST['payment_date'] ? $_POST['payment_date'] : NULL, //Set NULL if no payment date is provided
                'fiscal_year_id' => $_POST['fiscal_year_id'],
                'note' => $_POST['note']
            ];  
            //Update the contribution through the model
            $success = $this->treasurerModel->updateContribution($data);     
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
        $fiscal_years = $this->treasurerModel->getAllFiscalYears();      
        //Display the edited form with the current data
        include 'views/update_contribution.php';
    }        
    //Add a new fiscal year
    public function addNewFiscalYear() {
        try {
            //Add the new fiscal year through the model
            $newFiscalYear = $this->treasurerModel->addNewFiscalYear(); 
            
            //Redirect to the contributions overview (refresh)
            header("Location: index.php?page=contributions_overview");
            ob_end_flush();     //Flush the output buffer after the header (not strictly necessary)
            exit;             
        } catch (Exception $e) {
            echo "Error while adding a new fiscal year: " . $e->getMessage();
        }
    }
    //Display contributions per fiscal year
    public function showContributionsPerFiscalYear($fiscal_year_id = null) {
        try {
            //Check if a fiscal_year_id is provided. If not, try to retrieve it from the query string
            if ($fiscal_year_id === null) {
                $fiscal_year_id = isset($_GET['fiscal_year_id']) ? intval($_GET['fiscal_year_id']) : null;
            }
                //Retrieve contributions per fiscal year
            if ($fiscal_year_id) {
                $contributionsData = $this->treasurerModel->getContributionsPerFiscalYear($fiscal_year_id);
                $fiscal_year = $this->treasurerModel->getFiscalYearById($fiscal_year_id);
                $fiscal_years = $this->treasurerModel->getAllFiscalYears();
                //Retrieve the contributions and totals from the result
                $contributions = $contributionsData['contributions'];
                $income_total = $contributionsData['income_total'];
                $expenses_total = $contributionsData['expenses_total'];
                $taxes_total = $contributionsData['taxes_total'];
                $total = $contributionsData['total'];                
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
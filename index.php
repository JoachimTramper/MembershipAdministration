<?php
//Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//Include the necessary models and controllers
require_once 'Db.php';
require_once 'Models/LoginModel.php';
require_once 'Controllers/LoginController.php';
require_once 'Models/MembersModel.php';
require_once 'Controllers/MembersController.php';
require_once 'Models/SecretaryModel.php';
require_once 'Controllers/SecretaryController.php';
require_once 'Models/TreasurerModel.php';
require_once 'Controllers/TreasurerController.php';

//Create controller instances once
$loginController = new LoginController();
$membersController = new MembersController();
$secretaryController = new SecretaryController();
$treasurerController = new TreasurerController();

//Process POST requests that are not bound to a specific page
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $loginController->handleLogin();
    exit;
}

//Routing logic
if (isset($_GET['page'])) {
    $page = $_GET['page'];

    switch ($page) {

        //Members overview
        case 'members_overview':
            $membersController->showMembersOverview();
            break;

        //Dashboard
        case 'dashboard':
            $membersController->showDashboard();
            break;

        //Account management
        case 'account_management':
            $membersController->updateAccount();
            include 'views/account_management.php';
            break;

        //Update members
        case 'member_management':
            $membersController->getAllMembersUpdate();
            if (isset($_GET['action'])) {
                $action = $_GET['action'];

                switch ($action) {
                    case 'delete':
                        if (isset($_GET['id'])) {
                            $secretaryController->deleteMember($_GET['id']);
                        }
                        break;

                    case 'add':
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $data = $_POST;
                            $secretaryController->addMember($data);
                        }
                        break;

                    case 'update':
                        if (isset($_GET['id'])) {
                            $page = 'update_member';
                        }
                        break;

                    case 'add_family':
                        if (isset($_POST['family_name'], $_POST['new_family_address'])) {
                            $data = [
                                'family_name' => $_POST['family_name'],
                                'address' => $_POST['new_family_address']
                            ];
                            $secretaryController->addFamily($data);
                        }
                        break;

                    case 'delete_family':
                        if (isset($_POST['family_id'])) {
                            $familyId = $_POST['family_id'];
                            $secretaryController->deleteFamily($familyId);
                        }
                        break;
                }
            }

            break;

        //Update member
        case 'update_member':
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                $secretaryController->updateMember($id, $_POST);
            } else {
                echo "No member ID provided!";
            }
            break;

        //Contributions overview
        case 'contributions_overview':

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['add_fiscal_year'])) {
                    $treasurerController->addNewFiscalYear();
                } elseif (isset($_POST['add_contribution'])) {
                    $treasurerController->addContribution();
                }
            }

            $treasurerController->showContributionsOverview();

            if (isset($_GET['action'])) {
                $action = $_GET['action'];

                switch ($action) {
                    case 'delete':
                        if (isset($_GET['id'])) {
                            $treasurerController->deleteContribution($_GET['id']);
                        }
                        break;

                    case 'update':
                        if (isset($_GET['id'])) {
                            header("Location: index.php?page=update_contribution&id=" . $_GET['id']);
                            exit;
                        }
                        break;
                }
            }
            break;

        //Update contribution
        case 'update_contribution':
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                $treasurerController->updateContribution($id);
            } else {
                echo "No contribution ID provided!";
            }
            break;

        //Fiscal year overview
        case 'year_overview':
            if (isset($_GET['fiscal_year_id']) && !empty($_GET['fiscal_year_id'])) {
                $fiscal_year_id = intval($_GET['fiscal_year_id']);
                $treasurerController->showContributionsPerFiscalYear($fiscal_year_id);
            } else {
                echo "No fiscal year selected!";
                exit;
            }
            break;

        //Logout
        case 'logout':
            session_unset();
            session_destroy();
            header("Location: index.php");
            exit;
            
        //Unknown page
        default:
            include 'views/login.php';
            break;
    }
} else {
    //No page parameter present
    include 'views/login.php';
}
?>

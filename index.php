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
$ledenController = new LedenController();
$secretarisController = new SecretarisController();
$penningmeesterController = new PenningmeesterController();

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
            $ledenController->showLedenOverzicht();
            break;

        //Dashboard
        case 'dashboard':
            $ledenController->showDashboard();
            break;

        //Account management
        case 'account_management':
            $ledenController->vernieuwAccount();
            include 'views/account_management.php';
            break;

        //Update members
        case 'member_management':
            $ledenController->getAllLedenBewerken();
            if (isset($_GET['action'])) {
                $action = $_GET['action'];

                switch ($action) {
                    case 'verwijder':
                        if (isset($_GET['id'])) {
                            $secretarisController->verwijderLid($_GET['id']);
                        }
                        break;

                    case 'toevoegen':
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $data = $_POST;
                            $secretarisController->voegLidToe($data);
                        }
                        break;

                    case 'bewerk':
                        if (isset($_GET['id'])) {
                            $page = 'update_member';
                        }
                        break;

                    case 'toevoegen_familie':
                        if (isset($_POST['familie_naam'], $_POST['nieuwe_familie_adres'])) {
                            $data = [
                                'familie_naam' => $_POST['familie_naam'],
                                'adres' => $_POST['nieuwe_familie_adres']
                            ];
                            $secretarisController->voegFamilieToe($data);
                        }
                        break;

                    case 'verwijder_familie':
                        if (isset($_POST['familie_id'])) {
                            $familieId = $_POST['familie_id'];
                            $secretarisController->verwijderFamilie($familieId);
                        }
                        break;
                }
            }

            break;

        //Update member
        case 'update_member':
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                $secretarisController->bewerkLid($id, $_POST);
            } else {
                echo "No member ID provided!";
            }
            break;

        //Contributions overview
        case 'contributions_overview':

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['add_boekjaar'])) {
                    $penningmeesterController->voegNieuwBoekjaarToe();
                } elseif (isset($_POST['add_contributie'])) {
                    $penningmeesterController->voegContributieToe();
                }
            }

            $penningmeesterController->showContributiesOverzicht();

            if (isset($_GET['action'])) {
                $action = $_GET['action'];

                switch ($action) {
                    case 'verwijder':
                        if (isset($_GET['id'])) {
                            $penningmeesterController->verwijderContributie($_GET['id']);
                        }
                        break;

                    case 'bewerk':
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
                $penningmeesterController->wijzigContributie($id);
            } else {
                echo "No contribution ID provided!";
            }
            break;

        //Fiscal year overview
        case 'year_overview':
            if (isset($_GET['fiscal_year_id']) && !empty($_GET['fiscal_year_id'])) {
                $boekjaar_id = intval($_GET['fiscal_year_id']);
                $penningmeesterController->showContributiesPerBoekjaar($boekjaar_id);
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

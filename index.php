<?php
//Start de sessie.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//Include de benodigde modellen en controllers.
require_once 'Db.php';
require_once 'Models/LoginModel.php';
require_once 'Controllers/LoginController.php';
require_once 'Models/LedenModel.php';
require_once 'Controllers/LedenController.php';
require_once 'Models/SecretarisModel.php';
require_once 'Controllers/SecretarisController.php';
require_once 'Models/PenningmeesterModel.php';
require_once 'Controllers/PenningmeesterController.php';

//Maak eenmalig controller-instanties.
$loginController = new LoginController();
$ledenController = new LedenController();
$secretarisController = new SecretarisController();
$penningmeesterController = new PenningmeesterController();

//Verwerk POST-verzoeken die niet aan een specifieke pagina gebonden zijn.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $loginController->handleLogin();
    exit;
}

//Routinglogica.
if (isset($_GET['page'])) {
    $page = $_GET['page'];

    switch ($page) {

        //Ledenoverzicht.
        case 'ledenoverzicht':
            $ledenController->showLedenOverzicht();
            break;

        //Dashboard.
        case 'dashboard':
            $ledenController->showDashboard();
            break;

        //Accountbeheer.
        case 'account':
            $ledenController->vernieuwAccount();
            include 'views/account_beheer.php';
            break;

        //Leden bewerken.
        case 'leden_bewerken':
            $ledenController->getAllLedenBewerken();
            //$families = $secretarisController->getFamilies();
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
                            $page = 'bewerk_lid';
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

        //Lid bewerken.
        case 'bewerk_lid':
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                $secretarisController->bewerkLid($id, $_POST);
            } else {
                echo "Geen lid ID opgegeven!";
            }
            break;

        //Contributieoverzicht.
        case 'contributies_overzicht':

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
                            header("Location: index.php?page=bewerk_contributie&id=" . $_GET['id']);
                            exit;
                        }
                        break;
                }
            }
            break;

        //Bewerk contributie.
        case 'bewerk_contributie':
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                $penningmeesterController->wijzigContributie($id);
            } else {
                echo "Geen contributie ID opgegeven!";
            }
            break;

        //Boekjaar overzicht.
        case 'boekjaar_overzicht':
            if (isset($_GET['boekjaar_id']) && !empty($_GET['boekjaar_id'])) {
                $boekjaar_id = intval($_GET['boekjaar_id']);
                $penningmeesterController->showContributiesPerBoekjaar($boekjaar_id);
            } else {
                echo "Geen boekjaar geselecteerd!";
                exit;
            }
            break;

        //Logout.
        case 'logout':
            session_unset();
            session_destroy();
            header("Location: index.php");
            exit;
            
        //Onbekende pagina.
        default:
            include 'views/login.php';
            break;
    }
} else {
    //Geen page-parameter aanwezig.
    include 'views/login.php';
}
?>

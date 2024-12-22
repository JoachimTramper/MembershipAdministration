<?php

//Modellen en database includen. 
require_once dirname(__DIR__) . '/Models/LedenModel.php';
require_once dirname(__DIR__) . '/Models/SecretarisModel.php';
require_once dirname(__DIR__) . '/Db.php';

class LedenController {
    private $ledenModel;
    private $secretarisModel;

    public function __construct() {
        $db = new Database();
        $this->ledenModel = new LedenModel($db);
        $this->secretarisModel = new SecretarisModel($db);
    }
    //Account bijwerken.
    public function vernieuwAccount() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_account'])) {
            $newUsername = $_POST['gebruikersnaam'];
            $newPassword = $_POST['wachtwoord'];
            $userId = $_SESSION['user_id'];
    
            //Update accountgegevens via het model.
            $updateSuccess = $this->ledenModel->updateAccount($userId, $newUsername, $newPassword);
    
            if ($updateSuccess) {
                $_SESSION['username'] = $newUsername; // Update de sessie
                header("Location: index.php?page=dashboard");
                exit;
            } else {
                //Foutmelding als accountupdate mislukt.
                echo "<p>Er is een fout opgetreden bij het bijwerken van het account.</p>";
            }
        }
    }

    //Leden overzicht.
    public function showLedenOverzicht() {   
    
        try {
            //Haal de gegevens van alle leden op. 
            $leden = $this->ledenModel->getAllLeden();
            
            //Toon de view voor ledenoverzicht.
            include_once __DIR__ . '/../views/leden_overzicht.php';
            // Als er geen leden zijn, geef een bericht weer in de view.
            if (empty($leden)) {
                $errorMessage = "Geen ledengegevens beschikbaar.";
            } else {
                $errorMessage = null;
            } 
            //Toon foutmelding bij ophalen leden.
        } catch (Exception $e) {
            echo "Er is een fout opgetreden bij het ophalen van de leden: " . $e->getMessage();
        }
    }
    //Lid details ophalen.
    public function showLid($id) {
        $lid = $this->ledenModel->getLidById($id);
        return $lid;    //Return lid om in de view te gebruiken
    }
    //Alle leden ophalen.
    public function getAllLeden() {
        return $this->ledenModel->getAllLeden();
    }
    
    //Alle leden ophalen voor bewerken.
    public function getAllLedenBewerken() {
        try {
            //Haal de gegevens van alle leden op. 
            $leden = $this->ledenModel->getAllLedenBewerken();
            $roles = $this->secretarisModel->getRoles();
            $families = $this->secretarisModel->getFamilies(); 

            //Toon de view voor leden bewerken.
            include_once __DIR__ . '/../views/leden_bewerken.php';
            // Als er geen leden zijn, geef een bericht weer in de view.
            if (empty($leden)) {
                $errorMessage = "Geen ledengegevens beschikbaar.";
            } else {
                $errorMessage = null;
            } 
            //Toon foutmelding bij ophalen leden.
        } catch (Exception $e) {
            echo "Er is een fout opgetreden bij het ophalen van de leden: " . $e->getMessage();
        }
    }
    //Dashboard weergave voor gebruiker.
    public function showDashboard() {
        // Verkrijg de user_id, username en role uit de sessie.
        $user_id = $_SESSION['user_id'];
        $gebruiker = $_SESSION['username'];
        $rol = $_SESSION['role'];
    
        //Haal de familieleden met openstaande betalingen op (inclusief leden zonder betalingen).
        $leden = $this->ledenModel->getFamilieledenMetOpenstaandeBetalingen($user_id);
    
        //Controleer of er familieleden zijn gevonden.
        if (!empty($leden)) {
            //Bereken het totaal van openstaande betalingen.
            $totaal_openstaande_betalingen = 0;
            foreach ($leden as $lid) {
                if ($lid['bedrag'] !== null) {
                    $totaal_openstaande_betalingen += $lid['bedrag'];
                }
            }
        } else {
            //Geen familieleden of betalingen gevonden.
            $leden = []; //$leden naar lege array zetten om fouten in de view te voorkomen
            $totaal_openstaande_betalingen = 0;
        }
    
        //Laad de dashboard view.
        include_once __DIR__ . '/../views/dashboard.php';
    }

}

?>

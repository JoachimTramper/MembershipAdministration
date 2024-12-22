<?php
//Ob starten om problemen met redirecten te voorkomen. 
ob_start();
require_once dirname(__DIR__) . '/Models/PenningmeesterModel.php';
require_once dirname(__DIR__) . '/Db.php';

class PenningmeesterController {
    private $penningmeesterModel;

    public function __construct() {
        $db = new Database();
        $this->penningmeesterModel = new PenningmeesterModel($db);
    }

    //Toon het overzicht van contributies.
    public function showContributiesOverzicht() {
        try {
            //Haal contributies, boekjaren en familieleden op.
            $contributies = $this->penningmeesterModel->getAllContributies();
            $boekjaren = $this->penningmeesterModel->getAllBoekjaren(); 
            $familieleden = $this->penningmeesterModel->getAllFamilieleden();
            //Toon de view.
            include_once __DIR__ . '/../views/contributies_overzicht.php';
        } catch (Exception $e) {
            echo "Er is een fout opgetreden bij het ophalen van contributies, boekjaren of familieleden: " . $e->getMessage();
        }
    }
    //Voeg een nieuwe contributie toe.
    public function voegContributieToe() {
        try {
            //Verkrijg de gegevens uit het formulier en zet ze in een array. 
            $data = [
                'familielid_id' => empty($_POST['familielid_id']) ? NULL : $_POST['familielid_id'],
                'bedrag' => $_POST['bedrag'],
                'type' => $_POST['type'],
                'betaaldatum' => empty($_POST['betaaldatum']) ? NULL : $_POST['betaaldatum'],
                'boekjaar_id' => $_POST['boekjaar_id'],
                'aantekening' => $_POST['aantekening']
            ];       
            //Bereken bedrag als het familielid een korting heeft en er geen bedrag is ingevuld.
            if ($data['familielid_id'] && empty($data['bedrag'])) {
                //Haal de korting op voor het familielid.
                $kortingPercentage = $this->penningmeesterModel->getKorting($data['familielid_id']);    
                //Standaardbedrag is 100 euro, pas de korting toe.
                $data['bedrag'] = 100 * (1 - $kortingPercentage / 100);
            }     
            //Voeg de contributie toe via het model.
            $this->penningmeesterModel->addContributie($data);     
            //Redirect naar de overzichtspagina.
            header("Location: index.php?page=contributies_overzicht&id");
            exit;       
        } catch (Exception $e) {
            echo "Er is een fout opgetreden: " . $e->getMessage();
        }
    }    
    //Verwijder een contributie.
    public function verwijderContributie($id) {
        try {
            //Verwijder de contributie via het model.
            $this->penningmeesterModel->verwijderContributie($id);
            //Redirect naar het overzicht van contributies (refresh).
            header("Location: index.php?page=contributies_overzicht");
            ob_end_flush();     //Ob flushen na header (niet strikt noodzakelijk).
            exit;
        } catch (Exception $e) {
            echo "Fout bij het verwijderen van de contributie: " . $e->getMessage();
        }
    }
    //Update een bestaande contributie.
    public function wijzigContributie($id) {
        //Haal de huidige gegevens van de contributie op.
        $contributie = $this->penningmeesterModel->getContributieById($id);   
        if (!$contributie) {
            //Als er geen contributie wordt gevonden, toon een foutmelding. 
            echo "Contributie niet gevonden!";
            return;
        }     
        //Als het een POST-aanvraag is, werk dan de contributie bij.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Maak een array met de gegevens van de POST-aanvraag.
            $data = [
                'id' => $_POST['id'],
                'familielid_id' => $_POST['familielid_id'] ? $_POST['familielid_id'] : NULL, // Zet NULL als geen familielid_id is ingevuld
                'bedrag' => $_POST['bedrag'],
                'type' => $_POST['type'],
                'betaaldatum' => $_POST['betaaldatum'] ? $_POST['betaaldatum'] : NULL, // Zet NULL als geen betaaldatum is ingevuld
                'boekjaar_id' => $_POST['boekjaar_id'],
                'aantekening' => $_POST['aantekening']
            ];  
            //Update de contributie via het mode.
            $success = $this->penningmeesterModel->updateContributie($data);     
            if ($success) {
                //Redirect naar het overzicht van contributies als de update succesvol is.
                header("Location: index.php?page=contributies_overzicht");
                ob_end_flush();     //Ob flushen na header (niet strikt noodzakelijk).
                exit;
            } else {
                //Toon een foutmelding als de update niet gelukt is.
                echo "Fout bij het bijwerken van de contributie.";
            }
        }       
        // Haal de boekjaren op voor het formulier
        $boekjaren = $this->penningmeesterModel->getAllBoekjaren();      
        //Toon het bewerkte formulier met de huidige gegevens.
        include 'views/bewerk_contributie.php';
    }        
    //Voeg een nieuw boekjaar toe.     
    public function voegNieuwBoekjaarToe() {
        try {
            //Voeg het nieuwe boekjaar toe via het model.
            $nieuwJaar = $this->penningmeesterModel->addNieuwBoekjaar(); 
            
            //Redirect naar het contributie overzicht (refresh).
            header("Location: index.php?page=contributies_overzicht");
            ob_end_flush();     //Ob flushen na header (niet strict noodzakelijk).
            exit;             
        } catch (Exception $e) {
            echo "Fout bij het toevoegen van een nieuw boekjaar: " . $e->getMessage();
        }
    }
    //Toon contributies per boekjaar.
    public function showContributiesPerBoekjaar($boekjaar_id = null) {
        try {
            //Controleer of er een boekjaar_id is meegegeven. Zo niet, probeer deze uit de query string te halen
            if ($boekjaar_id === null) {
                $boekjaar_id = isset($_GET['boekjaar_id']) ? intval($_GET['boekjaar_id']) : null;
            }
                //Haal contributies per boekjaar op.
            if ($boekjaar_id) {
                $contributiesData = $this->penningmeesterModel->getContributiesPerBoekjaar($boekjaar_id);
                $boekjaar = $this->penningmeesterModel->getBoekjaarById($boekjaar_id);
                $boekjaren = $this->penningmeesterModel->getAllBoekjaren();
                // Haal de contributies en totalen uit het resultaat
                $contributies = $contributiesData['contributies'];
                $inkomsten_totaal = $contributiesData['inkomsten_totaal'];
                $uitgaven_totaal = $contributiesData['uitgaven_totaal'];
                $belastingen_totaal = $contributiesData['belastingen_totaal'];
                $totaal = $contributiesData['totaal'];                
            } else {
                //Toon een fout als geen boekjaar geselecteerd is.
                throw new Exception("Geen geldig boekjaar geselecteerd.");
            }  
            //Toon de view voor het geselecteerde boekjaar.
            include_once __DIR__ . '/../views/boekjaar_overzicht.php';
        } catch (Exception $e) {
            echo "Er is een fout opgetreden bij het ophalen van de gegevens: " . $e->getMessage();
        }
    }
    
    
}

?>
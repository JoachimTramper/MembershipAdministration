<?php

require_once dirname(__DIR__) . '/Models/SecretarisModel.php';
require_once dirname(__DIR__) . '/Models/LedenModel.php';
require_once dirname(__DIR__) . '/Db.php';

class SecretarisController {
    private $secretarisModel;
    private $ledenModel;

    public function __construct() {
        $db = new Database();
        $this->secretarisModel = new SecretarisModel($db);
        $this->ledenModel = new LedenModel($db);
    }

    //Voeg een lid toe.
    public function voegLidToe($data) {
        try {
            //Roep de functie aan in het model. 
            $this->secretarisModel->voegLidToe($data);
            header("Location: index.php?page=leden_bewerken");
            exit;
        } catch (Exception $e) {
            echo "Fout: " . $e->getMessage();
        }
    }
    //Verwijder een lid.
    public function verwijderLid($id) {
        try {
            $this->secretarisModel->verwijderLid($id);
            header("Location: index.php?page=leden_bewerken");
            exit;
        } catch (Exception $e) {
            echo "Fout: " . $e->getMessage();
        }
    }
    //Voeg een familie toe.
    public function voegFamilieToe($data) {
        try {
            $this->secretarisModel->voegFamilieToe($data);
            header("Location: index.php?page=leden_bewerken");
            exit;
        } catch (Exception $e) {
            echo "Fout: " . $e->getMessage();
        }
    }
    //Verwijder een familie.
    public function verwijderFamilie($familieId) {
        //Roep de functie aan in het model en zet deze in een variabele. 
        $result = $this->secretarisModel->deleteFamilie($familieId); 
        if ($result) {
            //Redirect naar de leden bewerken pagina na succesvol verwijderen (refresh). 
            header("Location: index.php?page=leden_bewerken");
            exit;
        } else {
            echo "Er is een fout opgetreden bij het verwijderen van de familie.";
        }
    }    
    //Haal alle families op.
    public function getFamilies() {
        try {
            $families = $this->secretarisModel->getFamilies(); 
            return $families;  // Return de lijst van families
        } catch (Exception $e) {
            die("Fout bij het ophalen van families: " . $e->getMessage());
        }
    }
    //Haal alle rollen op.
    public function getRoles() {
        try {
            $roles = $this->secretarisModel->getRoles();
            return $roles;
        } catch (Exception $e) {
            die("Fout bij het ophalen van rollen: " . $e->getMessage());
        }
    //Bewerk een lid. 
    }public function bewerkLid($id, $data = null) {
        //Data op null zetten voor het zowel verwerken van een POST-verzoek als voor het ophalen van gegevens.
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $data) {
            $data['id'] = $id;
            //Validatie van velden.
            $requiredFields = ['naam', 'adres', 'geboortedatum', 'gebruikersnaam', 'wachtwoord', 'familie_id', 'rol'];
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    throw new Exception("Veld '$field' is verplicht!");
                }
            }
            //Bijwerken van gegevens.
            $this->secretarisModel->updateLid($data);
            header("Location: index.php?page=leden_bewerken"); // Redirect bij succes
            exit;
        }    
        //Huidige gegevens ophalen.
        $lid = $this->ledenModel->getLidById($id);
        if (!$lid) {
            throw new Exception("Lid niet gevonden!");
        } 
        include 'views/bewerk_lid.php'; // Toon het formulier met bestaande gegevens
    }
    
}                



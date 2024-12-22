<?php

//Model en database includen. 
require_once 'Models/LoginModel.php';
require_once 'Db.php'; 

class LoginController {
    
    private $loginModel;
    
    public function __construct() {
        $db = new Database();
        $this->loginModel = new LoginModel($db);
    }
    //Functie om in te loggen. 
    public function handleLogin() {
        //Sessie starten als deze nog niet actief is.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        //Als het formulier wordt verzonden.
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            //Verkrijg de gebruikersnaam en wachtwoord van het formulier.
            $username = $_POST['uname'];
            $password = $_POST['psw'];

            //Roep de authenticate methode van het LoginModel aan.
            $loginResult = $this->loginModel->authenticate($username, $password);

            //Controleer of het resultaat een array is (succesvolle login).
            if (is_array($loginResult)) {
                //Sla gebruikersgegevens op in de sessie.
                $_SESSION['loggedIn'] = true;
                $_SESSION['user_id'] = $loginResult['id'];
                $_SESSION['username'] = $loginResult['gebruikersnaam'];
                $_SESSION['role'] = $loginResult['rol_soort'];
                //Redirecten naar dashboard. 
                header("Location: index.php?page=dashboard&id");
                exit; 
            } else {
                //Foutmelding voor geen succesvolle login. 
                echo "<p>Error: " . $loginResult . "</p>";
                header("Location: login.php"); //Terug naar de loginpagina redirecten. 
                exit;
            }
        }
    }
}
?>

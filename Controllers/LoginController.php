<?php

//Include model and database
require_once 'Models/LoginModel.php';
require_once 'Db.php'; 

class LoginController {
    
    private $loginModel;
    
    public function __construct() {
        $db = new Database();
        $this->loginModel = new LoginModel($db);
    }
    //Function to login
    public function handleLogin() {
        //Start session if it's inactive   
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        //If the form is being send
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            //Retrieve the username and password from the form
            $username = $_POST['uname'];
            $password = $_POST['psw'];

            //Call the authenticate method of the LoginModel
            $loginResult = $this->loginModel->authenticate($username, $password);

            //Check if the result is an array (successful login)
            if (is_array($loginResult)) {
                //Store user data in the session
                $_SESSION['loggedIn'] = true;
                $_SESSION['user_id'] = $loginResult['id'];
                $_SESSION['username'] = $loginResult['gebruikersnaam'];
                $_SESSION['role'] = $loginResult['rol_soort'];
                //Redirect to dashboard
                header("Location: index.php?page=dashboard&id");
                exit; 
            } else {
                //Error message for unsuccessful login 
                echo "<p>Error: " . $loginResult . "</p>";
                header("Location: Views/login.php"); //Redirect back to login page 
                exit;
            }
        }
    }
}
?>

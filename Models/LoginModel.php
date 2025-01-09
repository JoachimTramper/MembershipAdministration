<?php

class LoginModel
{
    private $db;
    //Constructor for connecting to the database
    public function __construct($db)
    {
        $this->db = $db;
    }
    //Function for authenticating a user
    public function authenticate($username, $password)
{
    try {
        //Establish a connection to the database
        $conn = $this->db->connect();
        //Prepare the SQL query to retrieve the user and role
        $stmt = $conn->prepare(
            "SELECT g.id, g.gebruikersnaam, g.wachtwoord, r.rol_soort 
            FROM gebruikers g 
            JOIN rol r ON g.rol = r.id 
            WHERE g.gebruikersnaam = :username");
        //Bind the username parameter to the query
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        //Execute query
        $stmt->execute();
        //Verify that exactly one user has been found
        if ($stmt->rowCount() === 1) {
            //Retrieve the user data
            $user = $stmt->fetch(PDO::FETCH_ASSOC);         
            //Compare the entered password with the stored password
            if (password_verify($password, $user['wachtwoord'])) {
                //Password is correct, return the user data
                return $user;
            } else {
                //Error message if the password is incorrect
                return 'Invalid password';
            }
        } else {
            //Error message if the user is not found
            return 'User not found';
        }
    } catch (PDOException $e) {
        //Error handling for database errors
        throw new Exception("Database error: " . $e->getMessage());
    }
}
    
}

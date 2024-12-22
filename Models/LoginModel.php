<?php

class LoginModel
{
    private $db;
    //Constructor voor het verbinden met de database.
    public function __construct($db)
    {
        $this->db = $db;
    }
    //Functie voor de authenticatie van een gebruiker.
    public function authenticate($username, $password)
{
    try {
        //Connectie maken met de database.
        $conn = $this->db->connect();
        //Bereid de SQL-query voor om de gebruiker en rol op te halen.
        $stmt = $conn->prepare(
            "SELECT g.id, g.gebruikersnaam, g.wachtwoord, r.rol_soort 
            FROM gebruikers g 
            JOIN rol r ON g.rol = r.id 
            WHERE g.gebruikersnaam = :username");
        // Bind de gebruikersnaam parameter aan de query
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        //Voer query uit.
        $stmt->execute();
        //Controleer of er exact 1 gebruiker is gevonden.
        if ($stmt->rowCount() === 1) {
            //Haal de gebruikersgegevens op.
            $user = $stmt->fetch(PDO::FETCH_ASSOC);         
            //Vergelijk het ingevoerde wachtwoord met het opgeslagen wachtwoord.
            if (password_verify($password, $user['wachtwoord'])) {
                //Wachtwoord is correct, retourneer de gebruikersgegevens.
                return $user;
            } else {
                //Foutmelding als het wachtwoord onjuist is.
                return 'Ongeldig paswoord';
            }
        } else {
            //Foutmelding als de gebruiker niet gevonden is.
            return 'Gebruiker niet gevonden';
        }
    } catch (PDOException $e) {
        //Foutafhandeling bij databasefouten.
        throw new Exception("Database error: " . $e->getMessage());
    }
}
    
}

<?php

//Start de sessie als deze nog niet is gestart.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//Controleer of de gebruiker is ingelogd.
if (!isset($_SESSION['role'])) {
    //Als de gebruiker niet is ingelogd, doorverwijzen naar de loginpagina.
    header('Location: index.php?page=login');
    exit;
}

include('header.php'); 
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wijzig Account</title>
    <link rel="stylesheet" href="../css/account_beheer.css">

</head>
<body>
<main>
    <h1>Wijzig Account</h1>
    <!-- Formulier container. -->
    <div class="form-container">
        <form method="post" action="index.php?page=account">
            <label for="username">Nieuwe gebruikersnaam:&emsp;</label>
            <input type="text" id="username" name="gebruikersnaam" required>
            <br><br>
            <label for="password">Nieuw wachtwoord:&emsp;</label>
            <input type="password" id="password" name="wachtwoord" required>
            <br><br>
            <button type="submit" name="update_account">Opslaan</button>
        </form>
    </div>
</main>
</body>
</html>
<?php include('footer.php'); ?>

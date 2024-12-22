<?php
//Start een nieuwe sessie als er nog geen sessie is gestart.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//Controleer of de gebruiker is ingelogd met de juiste rol. 
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'secretaris' && $_SESSION['role'] !== 'admin')) {
    //Gebruiker is niet ingelogd of heeft geen toegang.
    header('Location: index.php?page=login');
    exit;
}

?>
<?php include('header.php'); ?>
<link rel="stylesheet" href="../css/global.css">

<h2>Gegevens bewerken</h2>

<!-- Toon de gegevens van het lid met behulp van PHP. -->
<p><strong>ID:</strong> <?= htmlspecialchars($lid['id']) ?></p>
<p><strong>Naam:</strong> <?= htmlspecialchars($lid['naam']) ?></p>

<!-- Formulier voor het bewerken van lidgegevens. -->
<form action="index.php?page=bewerk_lid&id=<?= $lid['id'] ?>" method="POST">
    <!-- Invoer voor naam. -->
    <label for="naam">Naam:</label>
    <input type="text" name="naam" id="naam" value="<?= htmlspecialchars($lid['naam']) ?>" required>

    <!-- Invoer voor adres. -->
    <label for="adres">Adres:</label>
    <input type="text" name="adres" id="adres" value="<?= htmlspecialchars($lid['adres']) ?>" required>

    <!-- Invoer voor geboortedatum. -->
    <label for="geboortedatum">Geboortedatum:</label>
    <input type="date" name="geboortedatum" id="geboortedatum" value="<?= htmlspecialchars($lid['geboortedatum']) ?>" required>

    <!-- Invoer voor gebruikersnaam. -->
    <label for="gebruikersnaam">Gebruikersnaam:</label>
    <input type="text" name="gebruikersnaam" id="gebruikersnaam" value="<?= htmlspecialchars($lid['gebruikersnaam']) ?>" required>

    <!-- Invoer voor wachtwoord. -->
    <label for="wachtwoord">Wachtwoord:</label>
    <input type="password" name="wachtwoord" id="wachtwoord" value="<?= htmlspecialchars($lid['wachtwoord']) ?>">

    <!-- Invoer voor familie ID. -->
    <label for="familie_id">Familie ID:</label>
    <input type="text" name="familie_id" id="familie_id" value="<?= htmlspecialchars($lid['familie_id']) ?>" required>

    <!-- Keuzemenu voor rol. -->
    <label for="rol">Rol:</label>
    <select name="rol" id="rol">
        <option value="1" <?= $lid['rol'] == 1 ? 'selected' : '' ?>>Admin</option>
        <option value="2" <?= $lid['rol'] == 2 ? 'selected' : '' ?>>Secretaris</option>
        <option value="3" <?= $lid['rol'] == 3 ? 'selected' : '' ?>>Penningmeester</option>
        <option value="4" <?= $lid['rol'] == 4 ? 'selected' : '' ?>>Lid</option>
    </select>

    <!-- Opslaan knop. -->
    <button type="submit" name="bewerk_lid" value="bewerken">Opslaan</button>

</form>
<?php include('footer.php'); ?>
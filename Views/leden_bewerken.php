<?php

//Sessie starten als deze nog niet actief is.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//Controleer of de gebruiker is ingelogd met de juiste rol. 
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'secretaris' && $_SESSION['role'] !== 'admin')) {
    header('Location: index.php?page=login');
    exit;
}
include('header.php');
?>

<link rel="stylesheet" href="../css/global.css">
<!-- Formulier voor het toevoegen van een nieuw lid. -->
<h1>Leden bewerken</h1>
<h3>Nieuw lid toevoegen</h3>
<form method="post" action="index.php?page=leden_bewerken&action=toevoegen">
    <label for="naam">Naam:</label>
    <input type="text" name="naam" required>

    <label for="adres">Adres:</label>
    <input type="text" name="adres" required>

    <label for="geboortedatum">Geboortedatum:</label>
    <input type="date" name="geboortedatum" required>

    <label for="gebruikersnaam">Gebruikersnaam:</label>
    <input type="text" name="gebruikersnaam" required>

    <label for="wachtwoord">Wachtwoord:</label>
    <input type="password" name="wachtwoord" required>

    <!-- Dropdown voor bestaande familie. -->
    <label for="familie_id">Familie:</label>
    <select name="familie_id">
        <option value="">Familie</option>
        <?php
        //Families ophalen.
        foreach ($families as $familie) {
            //Toon naam en adres samen in de dropdown.
            echo "<option value='{$familie['id']}'>" . htmlspecialchars($familie['naam']) . " - " . htmlspecialchars($familie['adres']) . "</option>";
        }
        ?>
    </select>

    <label for="rol">Rol:</label>
    <select name="rol" required>
        <?php
    //Haal de rollen op uit de rol-tabel.
        foreach ($roles as $role) {
            echo "<option value='" . $role['id'] . "'>" . $role['rol_soort'] . "</option>";
        }
    ?>
    </select>
    <button type="submit">Lid toevoegen</button>
</form>


<!-- Formulier voor het toevoegen van een nieuwe familie. -->
<h3>Familie toevoegen</h3>
<form method="post" action="index.php?page=leden_bewerken&action=toevoegen_familie">
    <label for="familie_naam">Naam:</label>
    <input type="text" name="familie_naam" placeholder="Naam" required>

    <label for="nieuwe_familie_adres">Adres:</label>
    <input type="text" name="nieuwe_familie_adres" placeholder="Adres" required>

    <button type="submit">Familie toevoegen</button>
</form>

<!-- Formulier voor het verwijderen van een familie. -->
<h3>Familie Verwijderen</h3>
<form method="post" action="index.php?page=leden_bewerken&action=verwijder_familie">
    <input type="hidden" name="familie_id" value="<?= $familie['id'] ?>">
    <label for="familie_id">Selecteer een familie:</label>
    <select name="familie_id" required>
        <option value="">Kies een familie</option>
        <?php
        foreach ($families as $familie) {
            echo "<option value='{$familie['id']}'>" . htmlspecialchars($familie['naam']) . " - " . htmlspecialchars($familie['adres']) . "</option>";
        }
        ?>
    </select>
    <button type="submit" onclick="return confirm('Verwijder eerst alle familieleden, voordat je een familie verwijdert. Klik \'Cancel\' als u dit nog niet gedaan heeft, klik \'OK\' voor verwijderen.')">Familie verwijderen</button>
</form>
<hr>
<!-- Tabelweergave van leden. -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Naam</th>
            <th>Adres</th>
            <th>Geboortedatum</th>
            <th>Gebruikersnaam</th>
            <th>Wachtwoord</th>
            <th>Familie ID</th>
            <th>Soort Lid</th>
            <th>Rol</th>
            <th>Bewerken</th>
            <th>Verwijderen</th>
        </tr>
    </thead>
    <tbody>
        <!-- Controleer of er ledengegevens zijn. -->
        <?php if (isset($leden) && !empty($leden)): ?>
        <?php foreach ($leden as $lid): ?>
            <tr>
                <td><?= htmlspecialchars($lid['id']) ?></td>
                <td><?= htmlspecialchars($lid['naam']) ?></td>
                <td><?= htmlspecialchars($lid['adres']) ?></td> 
                <td><?= htmlspecialchars($lid['geboortedatum']) ?></td>
                <td><?= htmlspecialchars($lid['gebruikersnaam']) ?></td>
                <td>******</td> <!-- Wachtwoord wordt niet in plaintext getoond. -->
                <td><?= htmlspecialchars($lid['familie_id']) ?></td>
                <td><?= htmlspecialchars($lid['soort_lid']) ?></td> 
                <td><?= htmlspecialchars($lid['rol_soort']) ?></td>
                <td><a href="index.php?page=bewerk_lid&action=bewerk&id=<?= htmlspecialchars($lid['id']) ?>">Bewerken</a></td>
                <td><a href="index.php?page=leden_bewerken&action=verwijder&id=<?= $lid['id'] ?>" 
                onclick="return confirm('Pas eerst deze persoons contributies aan, verwijder naam en zet naam in \'aantekening\', klik \'Cancel\' als u dit nog niet gedaan heeft, klik \'OK\' voor verwijderen.')">Verwijderen</a></td>
            </tr>
        <?php endforeach; ?>
        <?php else: ?>
            <!-- Toon een bericht als er geen ledengegevens beschikbaar zijn. -->
            <tr>
                <td colspan="3">Geen ledengegevens beschikbaar.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include('footer.php'); ?>

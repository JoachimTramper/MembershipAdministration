<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//Controleer of de gebruiker is ingelogd met de juiste rol. 
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'penningmeester' && $_SESSION['role'] !== 'admin')) {
    header('Location: index.php?page=login');
    exit;
}

include('header.php');
?>

<link rel="stylesheet" href="../css/global.css">
<h1>Contributie overzicht</h1>
<!-- Dropdownformulier voor filtering op boekjaar. -->
<form method="GET" action="index.php?page=boekjaar_overzicht" class="dropdown-form dropdown-boekjaar-overzicht">
    <label for="boekjaar_id"><b>Overzicht per boekjaar:</b></label>
    <input type="hidden" name="page" value="boekjaar_overzicht">
    <select name="boekjaar_id" id="boekjaar_id" onchange="this.form.submit()">
        <option value="">Selecteer een boekjaar</option>
        <?php foreach ($boekjaren as $boekjaar): ?>
            <option value="<?= htmlspecialchars($boekjaar['id']) ?>" <?= isset($_GET['boekjaar_id']) && $_GET['boekjaar_id'] == $boekjaar['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($boekjaar['jaar']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<!-- Formulier om nieuwe contributie toe te voegen. -->
<h2>Voeg een nieuwe contributie toe</h2>
<form action="index.php?page=contributies_overzicht" method="POST">
    <label for="familielid_id">Familielid:</label>
    <select name="familielid_id" id="familielid_id">
        <option value="">Selecteer een familielid (optioneel)</option>
<?php foreach ($familieleden as $familielid) {echo "<option value='{$familielid['id']}'>" . htmlspecialchars($familielid['naam']) . "</option>";}?>
    </select>

    <label for="bedrag">Bedrag:</label>
    <input type="number" step="0.01" name="bedrag" id="bedrag" required>

    <label for="type">Type:</label>
    <select name="type" id="type">
        <option value="inkomsten">Inkomsten</option>
        <option value="uitgaven">Uitgaven</option>
        <option value="belastingen">Belastingen</option>
    </select>

    <label for="betaaldatum">Betaaldatum:</label>
    <input type="date" name="betaaldatum" id="betaaldatum">

        <!-- Dropdown voor boekjaar. -->
        <label for="boekjaar_id">Boekjaar:</label>
        <select name="boekjaar_id" required>
            <option value="">Selecteer een boekjaar</option>
<?php foreach ($boekjaren as $boekjaar) {echo "<option value='{$boekjaar['id']}'>" . htmlspecialchars($boekjaar['jaar']) . "</option>";}?>
        </select>

        <label for="aantekening">Aantekening:</label>
        <textarea name="aantekening" id="aantekening"></textarea>

    <button type="submit" name="add_contributie">Voeg contributie toe</button>
</form>
<br>
<br>

<!-- Formulier om een nieuw boekjaar toe te voegen. -->
<form method="post" action="index.php?page=contributies_overzicht">
    <button type="submit" name="add_boekjaar" onclick="return confirmAddBookjaar()">Nieuw Boekjaar Toevoegen</button>
</form>

<script>
    //Bevestiging bij toevoegen van een nieuw boekjaar.
    function confirmAddBookjaar() {
        return confirm("Weet u zeker dat u het eerstvolgende boekjaar wilt toevoegen aan de ledenadministratie?");
    }
</script>
<hr>

<!-- Tabel met bestaande contributies. -->
<h2>Contributies</h2>
<table>
    <thead>
        <tr>
            <th>Contributie ID</th>
            <th>Familielid</th>
            <th>Bedrag</th>
            <th>Type</th>
            <th>Betaaldatum</th>
            <th>Boekjaar</th>
            <th>Aantekening</th>
            <th>Acties</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($contributies as $contributie): ?>
                <td><?= htmlspecialchars($contributie['id']) ?></td>
                <td><?= htmlspecialchars($contributie['familielid_naam']) ?></td>
                <td>€ <?= number_format(htmlspecialchars($contributie['bedrag']), 2, ',', '.') ?></td></td>
                <td><?= htmlspecialchars($contributie['type']) ?></td>
                <td><?= htmlspecialchars($contributie['betaaldatum'] ?? 'Nog niet betaald.') ?></td> <!-- Standaard tekst voor als er nog niet betaald is -->
                <td><?= htmlspecialchars($contributie['boekjaar_jaar']) ?></td>
                <td><?= htmlspecialchars($contributie['aantekening']) ?></td>
                <td style="text-align: center;">
                <!-- Bewerken -->
                <a href="index.php?page=bewerk_contributie&action=bewerk&id=<?= $contributie['id'] ?>">Bewerken</a> |
                <!-- Verwijderen -->
                <a href="index.php?page=contributies_overzicht&action=verwijder&id=<?= $contributie['id'] ?>"
                onclick="return confirm('Weet je zeker dat je deze contributie wilt verwijderen?');">Verwijderen</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include('footer.php'); ?>
